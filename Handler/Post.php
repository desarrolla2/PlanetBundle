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

use Doctrine\ORM\EntityManager;
use Desarrolla2\Bundle\BlogBundle\Manager\PostManager;

/**
 *
 * Description of Post
 *
 * @author : Daniel González <daniel.gonzalez@freelancemadrid.es>
 * @file   : Post.php , UTF-8
 * @date   : May 18, 2013 , 2:32:55 AM
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
     * @var \Desarrolla2\Bundle\BlogBundle\Manager\PostManager
     */
    protected $pm;

    /**
     *
     * @param \Doctrine\ORM\EntityManager                        $em
     * @param \Desarrolla2\Bundle\BlogBundle\Manager\PostManager $pm
     */
    public function __construct(EntityManager $em, PostManager $pm)
    {
        $this->em = $em;
        $this->pm = $pm;
    }

    /**
     *
     */
    public function publishOne()
    {
        $post = $this->em->getRepository('BlogBundle:Post')->getOneRandomPrePublished();
        if ($post) {
            $this->pm->publish($post);
        }
    }

    /**
     *
     */
    public function cleanInvalidSources()
    {
        // @TODO: Clean invalid sources.
    }
}