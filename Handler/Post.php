<?php

/**
 * This file is part of the planetubuntu proyect.
 * 
 * Copyright (c)
 * Daniel González <daniel.gonzalez@freelancemadrid.es> 
 * 
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Handler;

/**
 * 
 * Description of Post
 *
 * @author : Daniel González <daniel.gonzalez@freelancemadrid.es> 
 * @file : Post.php , UTF-8
 * @date : May 18, 2013 , 2:32:55 AM
 */
class Post
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager; 
     */
    protected $em;

    /**
     * 
     * @param \Doctrine\ORM\EntityManager $em
     * @param \Desarrolla2\RSSClient\RSSClientInterface $rss
     */
    public function __construct(EntityManager $em, RSSClientInterface $rss)
    {
        $this->em = $em;
    }

    /**
     * 
     */
    public function run()
    {
        $item = $this->em->getRepository('Post')->getOnePrePublished();
        if ($item) {
            
        }
    }

}