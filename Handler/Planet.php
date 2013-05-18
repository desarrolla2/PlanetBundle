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

Use Doctrine\ORM\EntityManager;
use Desarrolla2\Bundle\BlogBundle\Entity\Post;
use Desarrolla2\Bundle\BlogBundle\Entity\Author;
use Desarrolla2\Bundle\PlanetBundle\Entity\PostGuid;
use Desarrolla2\Bundle\PlanetBundle\Util\String;
use Desarrolla2\Bundle\BlogBundle\Model\PostStatus;
use Desarrolla2\RSSClient\RSSClientInterface;
use Desarrolla2\RSSClient\Node\Node;
use \DateTime;

/**
 * 
 * Description of PlanetClient
 *
 * @author : Daniel González Cerviño <daniel.gonzalez@freelancemadrid.es>  
 * @file : PlanetClient.php , UTF-8
 * @date : Mar 4, 2013 , 2:25:29 PM
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
     * @var \DateTime 
     */
    protected $startDate;

    /**
     * 
     * @param \Doctrine\ORM\EntityManager $em
     * @param \Desarrolla2\RSSClient\RSSClientInterface $rss
     */
    public function __construct(EntityManager $em, RSSClientInterface $rss)
    {
        $this->em = $em;
        $this->rss = $rss;
        $this->startDate = new \DateTime('01/22/2013');
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
                                        if ($feed->getPubDate() < $this->getStartDate()) {
                                            continue;
                                        }
                                        $guid = $this->getGuid($feed);
                                        if (!$guid) {
                                            $this->createPost($feed);
                                            $this->notify(' > New "' . $feed->getTitle() . '"');
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
        ));
    }

    /**
     * 
     * @return type
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * 
     * @param \Desarrolla2\RSSClient\Node\Node $feed
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
        $this->em->persist($entity);       

        $this->setGUID($entity, $feed->getGuid());        
        $this->setTags($entity, $tags = $feed->getCategories());        
        $this->setAuthor($entity, $feed->getAuthor());
        $this->em->flush();
        
    }

    /**
     * 
     * @param type $entity
     * @param type $tags
     */
    protected function setTags($entity, $tags)
    {
        foreach ($tags as $tagName) {
            $tagName = trim(strtolower($tagName));
            if ($tagName) {
                $tag = $this->em->getRepository('BlogBundle:Tag')->getOrCreateByName($tagName);
                $entity->addTag($tag);
                $this->em->getRepository('BlogBundle:Tag')->indexTagItemsForTag($tag);
            }
        }
        $this->em->persist($entity);
    }

    /**
     * 
     * @param \Desarrolla2\Bundle\PlanetBundle\Entity\PostGuid $guid
     */
    protected function setGUID($entity, $guidString)
    {
        $guid = new PostGuid();
        $guid->setGuid($guidString);
        $guid->setPost($entity);
        $guid->setPublishedAt(new DateTime());
        $this->em->persist($guid);
        $this->em->persist($entity);
    }

    /**
     * 
     * @return \Desarrolla2\Bundle\BlogBundle\Entity\Author
     */
    protected function setAuthor($entity, $email)
    {
        if ($email) {
            $author = $this->em->getRepository('PlanetBundle:PostGuid')->findOneBy(
                    array(
                        'email' => $email,
            ));
            if (!$author) {
                $author = new Author();
                $author->setEmail($email);
                $author->setName($email);
                $this->em->persist($author);
            }
            $entity->setAuthor($author);
            $this->em->persist($entity);
        }
    }

    /**
     * 
     * @param type $log
     */
    protected function notify($log)
    {
        echo $log . PHP_EOL;
    }

    /**
     * 
     * @param type $string
     * @return type
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
        $string = strip_tags($string, '<pre><cite><code><em><i><ul><li><ol><small><span><strike><a>' .
                '<b><p><br><br/><img><h4><h5><h3><h2>' .
                '<table><tr><td><ht>'
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
        $string = strip_tags($string, '<ul><li><ol><b><p><br><br/><img><h4><h5><h3><h2>' .
                '<table><tr><td><ht>'
        );
        $string = String::truncate($string, 500);
        return $this->doClean($string);
    }

}
