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

namespace Desarrolla2\Bundle\PlanetBundle\Service;

use Doctrine\ORM\EntityManager;
use Desarrolla2\Bundle\BlogBundle\Manager\PostManager;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 *
 * Description of Post
 *
 * @author : Daniel González <daniel.gonzalez@freelancemadrid.es>
 * @file   : Post.php , UTF-8
 * @date   : May 18, 2013 , 2:32:55 AM
 */
class Post extends AbstractService
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
    public function validateSources()
    {
        $total = $this->em->getRepository('BlogBundle:Post')->countPublishedWithSource();
        ldd($total);
        // contar el total de publicados con fuentes.
        // recuperar la lista de publicados que tienen fuentes en pasos de 20
        $posts = array();
        foreach ($posts as $post) {
            // pedirle a guzzle si la url responde algo que no es un 200
            // generar logs
            $error = $this->em->getRepository('BlogBundle:PostSourceError')->getForPost($post);
            if (!$error->getErrors() >= 3) {
                // limpiar la fuente del error;
            }
            $error->increase();

            $this->em->persist($error);
        }

        $this->em->flush();
    }

    private function getPostPublishedWithSource()
    {
    }


    private function countPostPublishedWithSource()
    {
    }
}