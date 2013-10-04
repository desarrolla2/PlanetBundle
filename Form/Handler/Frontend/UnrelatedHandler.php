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

namespace Desarrolla2\Bundle\PlanetBundle\Form\Handler\Frontend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Desarrolla2\Bundle\PlanetBundle\Entity\Unrelated;
use Desarrolla2\Bundle\BlogBundle\Entity\Post;
use Doctrine\ORM\EntityManager;

/**
 *
 * Description of UnrelatedHandler
 *
 * @author : Daniel González Cerviño <daniel.gonzalez@freelancemadrid.es>
 * @file   : UnrelatedHandler.php , UTF-8
 * @date   : Mar 25, 2013 , 6:21:23 PM
 */
class UnrelatedHandler
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \Desarrolla2\Bundle\PlanetBundle\Form\Type\Frontend\NewBlogType
     */
    protected $form;

    /**
     * @var \Desarrolla2\Bundle\BlogBundle\Entity\Post
     */
    protected $post;

    public function __construct(EntityManager $em, Request $request, Form $form, Post $post)
    {
        $this->em = $em;
        $this->request = $request;
        $this->form = $form;
        $this->post = $post;
    }

    /**
     * Process forn
     */
    public function process()
    {
        $this->form->bind($this->request);
        if ($this->form->isValid()) {
            $unrelated = new Unrelated();
            $unrelated->setPost($this->post);
            $this->em->persist($unrelated);
            $this->em->flush();

            return true;
        }

        return false;
    }
}
