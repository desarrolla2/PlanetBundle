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

use Doctrine\ORM\EntityManager;
use Desarrolla2\Bundle\BlogBundle\Entity\Post;
use Desarrolla2\Bundle\BlogBundle\Entity\Author;
use Desarrolla2\Bundle\PlanetBundle\Entity\PostGuid;
use Desarrolla2\Bundle\BlogBundle\Entity\Link;
use Desarrolla2\Bundle\PlanetBundle\Entity\LinkPost;
use Desarrolla2\Bundle\PlanetBundle\Util\String;
use Desarrolla2\Bundle\BlogBundle\Model\PostStatus;
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
    protected $rss;

    /**
     *
     * @param \Doctrine\ORM\EntityManager               $em
     * @param \Desarrolla2\RSSClient\RSSClientInterface $rss
     */
    public function __construct(EntityManager $em, RSSClientInterface $rss)
    {
        $this->em = $em;
        $this->rss = $rss;
    }

    /**
     *
     */
    public function run()
    {
        $links = $this->getLinks();
        if ($links) {
            if (count($links)) {
                foreach ($links as $link) {
                    $this->notify('Feed: ' . $link->getName() . ' ' . $link->getRSS());
                    if ($link->getRSS()) {
                        $this->rss->setFeed($link->getRSS());
                        try {
                            $feeds = $this->rss->fetch();
                            if ($feeds) {
                                if ($feeds->count()) {
                                    foreach ($feeds as $feed) {
                                        $guid = $this->getGuid($feed);
                                        if (!$guid) {
                                            $this->notify(' > New post "' . $feed->getTitle() . '"');
                                            $post = $this->createPost($feed);
                                            $this->createLinkPost($link, $post);
                                        }
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            $this->notify('## error : ' . $e->getMessage());
                        }
                    }
                }
            }
        }
    }

    /**
     *
     * @return type
     */
    protected function getLinks()
    {
        $links = $this->em->getRepository('BlogBundle:Link')->getActive();

        return $links;
    }

    /**
     *
     * @param type $feed
     * @return type
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
        $entity->setIntro($this->doCleanExtract($feed->getDescription()));
        $entity->setContent($this->doCleanText($feed->getDescription()));
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
     * @param string $string
     * @return string
     */
    protected function doClean($string)
    {
        $string = str_replace('<p>&nbsp;</p>', '', $string);
        $string = trim(str_replace('<p></p>', '', $string));
        $string = preg_replace('/\s\s+/', ' ', $string);
        $string = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $string);

        return trim($string);
    }

    /**
     *
     * @param string $string
     * @return string
     */
    protected function doCleanText($string)
    {
        $string = strip_tags(
            $string,
            '<pre><cite><code><em><i><ul><li><ol><small><span><strike><a>' .
            '<b><p><br><br/><img><h4><h5><h3><h2>'
        );

        return $this->doClean($string);
    }

    /**
     *
     * @param string $string
     * @return string
     */
    protected function doCleanExtract($string)
    {
        $string = strip_tags(
            $string,
            '<ul><li><ol><b><p><br><h4><h5><h3><h2>' .
            '<table><tr><td><ht>'
        );

        return $this->doClean(String::truncate($string, 500));
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
