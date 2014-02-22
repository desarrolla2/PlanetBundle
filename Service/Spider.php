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
use Desarrolla2\Bundle\PlanetBundle\Entity\PostLink;
use Desarrolla2\Bundle\PlanetBundle\Entity\PostGuid;
use Desarrolla2\Bundle\PlanetBundle\Event\PostEvent;
use Desarrolla2\Bundle\PlanetBundle\Event\PostEvents;
use Desarrolla2\Bundle\PlanetBundle\Helper\PostHelper;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use \DOMDocument;
use \DateTime;
use FastFeed\FastFeedInterface;
use FastFeed\Item;

/**
 * Class Spider
 *
 * @author Daniel González <daniel.gonzalez@freelancemadrid.es>
 */
class Spider extends AbstractService
{
    /**
     *
     * @var EntityManager;
     */
    protected $em;

    /**
     * @var RSSClientInterface
     */
    protected $fastFeed;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $conn;

    /**
     * @param EntityManager            $em
     * @param EventDispatcherInterface $dispatcher
     * @param FastFeedInterface        $fastFeed
     * @param LoggerInterface          $logger
     */
    public function __construct(
        EntityManager $em,
        EventDispatcherInterface $dispatcher,
        FastFeedInterface $fastFeed,
        LoggerInterface $logger
    ) {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
        $this->fastFeed = $fastFeed;
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
            try {
                $this->retrieveItemsForLink($link);
            } catch (\Exception $e) {
                $this->notify($e->getMessage(), LogLevel::ERROR);
            }
        }
    }

    /**
     * Retrieve and save items for link
     *
     * @param Link $link
     *
     * @throws \Exception
     */
    public function retrieveItemsForLink(Link $link)
    {

        $this->notify('Retrieving data for "' . $link->getName() . '"');
        if (!$link->getRSS()) {
            $this->notify('Link with id "' . $link->getId() . '" have`nt RSS', LogLevel::WARNING);

            return;
        }
        $this->fastFeed->setFeed($link->getName(), $link->getRSS());
        $items = $this->fastFeed->fetch($link->getName());
        if (!$items) {
            $this->notify('Link with id "' . $link->getId() . '" have`nt elements', LogLevel::WARNING);

            return;
        }
        if (!count ($items)) {
            $this->notify('Link with id "' . $link->getId() . '" have`nt elements', LogLevel::WARNING);
        }
        $this->notify(' >> found "' . count ($items). '" elements');
        foreach ($items as $item) {
            $guid = $this->getGuid($item);
            if (!$guid) {
                $this->parseFeed($link, $item);
            }
        }
    }

    /**
     * @param Link $link
     * @param Item $item
     *
     * @return Post
     */
    public function parseFeed(Link $link, Item $item)
    {
        $this->conn->beginTransaction();
        try {
            $this->notify(' > New post "' . $item->getName() . '"');
            $post = $this->createPost($item);
            $this->createPostLink($link, $post);

            $this->dispatcher->dispatch(
                PostEvents::CREATED,
                new PostEvent($post)
            );
            $this->conn->commit();

            return $post;

        } catch (\Exception $e) {
            $this->conn->rollback();
            $this->notify($e->getMessage(), LogLevel::ERROR);
            // throw $e;
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
     * @param Item $item
     *
     * @return null|object
     */
    protected function getGuid(Item $item)
    {
        return $this->em->getRepository('PlanetBundle:PostGuid')->findOneBy(
            array(
                'guid' => $item->getId(),
            )
        );
    }

    /**
     * @param Link $link
     * @param Post $post
     * @param Post $post
     */
    protected function createPostLink(Link $link, Post $post)
    {
        $entity = new PostLink();
        $entity->setPost($post);
        $entity->setLink($link);

        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * @param Item $item
     *
     * @return Post
     */
    protected function createPost(Item $item)
    {
        $entity = new Post();

        $entity->setName($item->getName());
        $entity->setIntro(PostHelper::doCleanIntro($item->getIntro()));
        $entity->setContent(PostHelper::doCleanContent($item->getContent()));
        $entity->setStatus(PostStatus::PUBLISHED);
        $entity->setSource($item->getSource());
        $entity->setPublishedAt($item->getDate());
        $entity->setCreatedAt($item->getDate());
        $entity->setImage($item->getImage());
        $this->em->persist($entity);

        $this->setTags($entity, $tags = $item->getTags());
        if ($item->getAuthor()) {
            $this->setAuthor($entity, $item->getAuthor());
        }

        $this->em->persist($entity);
        $this->em->flush();

        $this->setGUID($entity, $item->getId());
        $this->em->flush();

        return $entity;
    }

    /**
     * @param array $tags
     *
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
     *
     * @internal param \Desarrolla2\Bundle\PlanetBundle\Entity\PostGuid $guid
     */
    protected function setGUID($entity, $guidString)
    {
        $guid = new PostGuid();
        $guid->setGuid($guidString);
        $guid->setPost($entity);
        $this->em->persist($guid);
    }

    /**
     *
     * @param $entity
     * @param $email
     *
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
}