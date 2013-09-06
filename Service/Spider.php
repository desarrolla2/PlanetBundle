<?php
/**
 * This file is part of the planetandroid project.
 *
 * Copyright (c)
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Service;


use Desarrolla2\Bundle\BlogBundle\Entity\Author;
use Desarrolla2\Bundle\BlogBundle\Entity\Link;
use Desarrolla2\Bundle\BlogBundle\Entity\Post;
use Desarrolla2\Bundle\BlogBundle\Model\PostStatus;
use Desarrolla2\Bundle\PlanetBundle\Entity\LinkPost;
use Desarrolla2\Bundle\PlanetBundle\Entity\PostGuid;
use Desarrolla2\Bundle\PlanetBundle\Event\PostEvent;
use Desarrolla2\Bundle\PlanetBundle\Event\PostEvents;
use Desarrolla2\Bundle\PlanetBundle\Helper\PostHelper;
use Desarrolla2\RSSClient\Node\Node;
use Desarrolla2\RSSClient\RSSClientInterface;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use \DOMDocument;
use \DateTime;

/**
 * Class Spider
 *
 * @author Daniel González <daniel.gonzalez@freelancemadrid.es>
 */

class Spider
{
    /**
     *
     * @var EntityManager;
     */
    protected $em;

    /**
     * @var RSSClientInterface
     */
    protected $client;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var LoggerInterface;
     */
    protected $logger;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $conn;

    /**
     * @param EntityManager      $em
     * @param EventDispatcher    $dispatcher
     * @param RSSClientInterface $client
     * @param LoggerInterface    $logger
     */
    public function __construct(
        EntityManager $em,
        EventDispatcher $dispatcher,
        RSSClientInterface $client,
        LoggerInterface $logger
    ) {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
        $this->client = $client;
        $this->logger = $logger;
        $this->conn = $this->em->getConnection();
    }

    /**
     * Retrieve new items in database links
     *
     * @throws \Exception
     */
    public function run()
    {
        $links = $this->getLinks();
        if (!$links) {
            $this->notify('No Links', LogLevel::WARNING);

            return;
        }

        foreach ($links as $link) {
            $this->retrieveItemsForLink($link);
        }
    }

    /**
     * Retrieve and save items for link
     *
     * @param Link $link
     * @throws \Exception
     */
    public function retrieveItemsForLink(Link $link)
    {

        $this->notify('Retrieving data for "' . $link->getName() . '"');
        if (!$link->getRSS()) {
            $this->notify('Link with id "' . $link->getId() . '" have`nt RSS', LogLevel::WARNING);

            return;
        }

        $this->client->setFeed($link->getRSS(), $link->getName());
        $feeds = $this->client->fetch($link->getName(), 25);
        if (!$feeds) {
            $this->notify('Link with id "' . $link->getId() . '" have`nt elements', LogLevel::WARNING);

            return;
        }
        if (!$feeds->count()) {
            $this->notify('Link with id "' . $link->getId() . '" have`nt elements', LogLevel::WARNING);
        }
        $this->notify(' >> found "' . $feeds->count() . '" elements');
        foreach ($feeds as $feed) {
            $guid = $this->getGuid($feed);
            if (!$guid) {
                $this->parseFeed($link, $feed);
            }
        }
    }

    /**
     * @param Link   $link
     * @param string $feed
     * @throws \Exception
     */
    public function parseFeed(Link $link, $feed)
    {
        $this->conn->beginTransaction();
        try {
            $this->notify(' > New post "' . $feed->getTitle() . '"');
            $post = $this->createPost($feed);
            $this->createLinkPost($link, $post);
            $this->dispatcher->dispatch(
                PostEvents::CREATED,
                new PostEvent($post)
            );
            $this->conn->commit();
        } catch (\Exception $e) {
            $this->notify($e->getMessage(), LogLevel::ERROR);
            $this->conn->rollback();
            throw $e;
        }
    }


    /**
     *
     * @return array
     */
    protected function getLinks()
    {
        $links = $this->em->getRepository('BlogBundle:Link')->getActive();

        return $links;
    }

    /**
     *
     * @param string $feed
     * @return PostGuid
     */
    protected function getGuid($feed)
    {
        return $this->em->getRepository('PlanetBundle:PostGuid')->findOneBy(
            array(
                'guid' => $feed->getGuid(),
            )
        );
    }

    /**
     * @param Link $link
     * @param Post $post
     * @param Post $post
     */
    protected function createLinkPost(Link $link, Post $post)
    {
        $entity = new LinkPost();
        $entity->setPost($post);
        $entity->setLink($link);

        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * @param Node $feed
     * @return Post
     */
    protected function createPost(Node $feed)
    {
        $entity = new Post();

        $entity->setName($feed->getTitle());
        $entity->setIntro(PostHelper::doCleanIntro($feed->getDescription()));
        $entity->setContent(PostHelper::doCleanContent($feed->getDescription()));
        $entity->setStatus(PostStatus::PRE_PUBLISHED);
        $entity->setSource($feed->getLink());
        $entity->setPublishedAt(new DateTime());
        $entity->setImage($this->getImage($feed->getDescription()));
        $this->em->persist($entity);

        $this->setGUID($entity, $feed->getGuid());
        $this->setTags($entity, $tags = $feed->getCategories());
        if ($feed->getAuthor()) {
            $this->setAuthor($entity, $feed->getAuthor());
        }

        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    /**
     *
     * @param $string
     * @return
     * @internal param \Desarrolla2\Bundle\PlanetBundle\Handler\type $entity
     */
    protected function getImage($string)
    {
        $DOM = new DOMDocument();
        $DOM->loadHTML($string);
        $DOM->preserveWhiteSpace = false;
        $images = $DOM->getElementsByTagName('img');
        foreach ($images as $image) {
            $src = $image->getAttribute('src');
            if ($this->isGifImage($src)) {
                continue;
            }

            return $src;
        }
    }

    /**
     * @param array $tags
     * @return array
     */
    protected function cleanTags($tags)
    {
        $tags1 = array();
        foreach ($tags as $tagName) {
            $tagName = trim(strtolower($tagName));
            if (!in_array($tagName, $tags1)) {
                $tags1[] = $tagName;
            }
        }

        return $tags1;
    }

    /**
     *
     * @param Post  $entity
     * @param array $tags
     */
    protected function setTags(Post $entity, $tags)
    {
        $tags = $this->cleanTags($tags);
        foreach ($tags as $tagName) {
            if ($tagName) {
                $this->notify(' > > Tag "' . $tagName . '"');
                $tag = $this->em->getRepository('BlogBundle:Tag')->getOrCreateByName($tagName);
                $entity->addTag($tag);
                $this->em->getRepository('BlogBundle:Tag')->indexTagItemsForTag($tag);
            }
        }
    }

    /**
     *
     * @param $entity
     * @param $guidString
     * @internal param \Desarrolla2\Bundle\PlanetBundle\Entity\PostGuid $guid
     */
    protected function setGUID($entity, $guidString)
    {
        $guid = new PostGuid();
        $guid->setGuid($guidString);
        $guid->setPost($entity);
        $guid->setPublishedAt(new DateTime());
        $this->em->persist($guid);
    }

    /**
     *
     * @param $entity
     * @param $email
     * @return \Desarrolla2\Bundle\BlogBundle\Entity\Author
     */
    protected function setAuthor($entity, $email)
    {
        $this->notify(' > > Author "' . $email . '"');
        $author = $this->em->getRepository('BlogBundle:Author')->findOneBy(
            array(
                'email' => $email,
            )
        );
        if (!$author) {
            $author = new Author();
            $author->setEmail($email);
            $author->setName($email);
            $this->em->persist($author);
        }
        $entity->setAuthor($author);
    }

    /**
     *
     * @param string $imageUrl
     * @return bool
     */
    private function isGifImage($imageUrl)
    {
        $pattern = '#\.gif#';

        return (bool)preg_match($pattern, $imageUrl);
    }

    /**
     * @param string $message
     * @param string $logLevel
     * @param array  $context
     */
    private function notify($message, $logLevel = LogLevel::INFO, $context = array())
    {
        $this->logger->log($logLevel, '[spider] ' . $message, $context);
    }
}