<?php

/**
 * This file is part of the planetubuntu proyect.
 *
 * Copyright (c)
 * Daniel González Cerviño <daniel.gonzalez@freelancemadrid.es>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Handler;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Desarrolla2\Bundle\BlogBundle\Entity\Post;
use Desarrolla2\Bundle\BlogBundle\Entity\Author;
use Desarrolla2\Bundle\BlogBundle\Entity\Link;
use Desarrolla2\Bundle\BlogBundle\Model\PostStatus;
use Desarrolla2\Bundle\PlanetBundle\Entity\PostGuid;
use Desarrolla2\Bundle\PlanetBundle\Entity\LinkPost;
use Desarrolla2\Bundle\PlanetBundle\Helper\PostHelper;
use Desarrolla2\Bundle\PlanetBundle\Event\PostEvent;
use Desarrolla2\Bundle\PlanetBundle\Event\PostEvents;
use Desarrolla2\RSSClient\RSSClientInterface;
use Desarrolla2\RSSClient\Node\Node;
use \DOMDocument;
use \DateTime;

/**
 *
 * Description of Planet
 *
 * @author : Daniel González Cerviño <daniel.gonzalez@freelancemadrid.es>
 */
class Planet
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager;
     */
    protected $em;

    /**
     * @var \Desarrolla2\RSSClient\RSSClientInterface
     */
    protected $client;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatcher;

    protected $logger;

    /**
     *
     * @param \Doctrine\ORM\EntityManager                        $em
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     * @param \Desarrolla2\RSSClient\RSSClientInterface          $client
     */
    public function __construct(EntityManager $em, EventDispatcher $dispatcher, RSSClientInterface $client)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
        $this->client = $client;
    }

    /**
     * Run Planet Service
     */
    public function run()
    {
        $links = $this->getLinks();
        if (!$links) {
            $this->notify('No Links');

            return;
        }
        foreach ($links as $link) {
            $this->notify('Feed: ' . $link->getName() . ' ' . $link->getRSS());
            if (!$link->getRSS()) {
                // exception
                continue;
            }
            $this->client->setFeed($link->getRSS());
            try {
                $feeds = $this->client->fetch();
                if (!$feeds) {
                    continue;
                }
                if ($feeds->count()) {
                    foreach ($feeds as $feed) {
                        $guid = $this->getGuid($feed);
                        if ($guid) {
                            continue;
                        }
                        $this->notify(' > New post "' . $feed->getTitle() . '"');
                        $post = $this->createPost($feed);
                        $this->createLinkPost($link, $post);
                        $this->dispatcher->dispatch(
                            PostEvents::CREATED,
                            new PostEvent($post)
                        );
                    }
                }
            } catch (\Exception $e) {
                $this->notify('## error : ' . $e->getMessage());
            }
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
     *
     * @param \Desarrolla2\RSSClient\Node\Node $feed
     * @return \Desarrolla2\Bundle\BlogBundle\Entity\Post
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
        $this->setAuthor($entity, $feed->getAuthor());

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
        //$this->em->persist($entity);
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
        //$this->em->persist($entity);
    }

    /**
     *
     * @param $entity
     * @param $email
     * @return \Desarrolla2\Bundle\BlogBundle\Entity\Author
     */
    protected function setAuthor($entity, $email)
    {
        return;
        if ($email) {
            $author = $this->em->getRepository('PlanetBundle:PostGuid')->findOneBy(
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
            //$this->em->persist($entity);
        }
    }

    /**
     *
     * @param string $log
     */
    protected function notify($log)
    {
        echo $log . PHP_EOL;
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
}
