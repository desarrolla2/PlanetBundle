<?php

/**
 * This file is part of the desarrolla2 proyect.
 * 
 * Copyright (c)
 * Daniel González Cerviño <daniel.gonzalez@freelancemadrid.es>  
 * 
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Desarrolla2\Bundle\PlanetBundle\Form\Type\NewBlogType;
use Desarrolla2\Bundle\PlanetBundle\Form\Model\NewBlogModel;
use Desarrolla2\Bundle\PlanetBundle\Form\Handler\NewBlogHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * 
 * Description of NewBlogController
 *
 */
class NewBlogController extends Controller {

    /**
     *
     *
     * @Route("/new-blog", name="_new_blog")
     * @Template()
     */
    public function indexAction(Request $request) {

        $form = $this->createForm(new NewBlogType(), new NewBlogModel());
        if ($request->getMethod() == 'POST') {
            $handler = new NewBlogHandler($request, $form, $this->get('planet.newblog.handler'));
            if ($handler->process()) {
                $this->get('session')
                        ->getFlashBag()
                        ->add('success', 'Hemos recibido su mensaje');
                return new RedirectResponse($this->generateUrl('_message'), 302);
            }
        }
        return array(
            'form' => $form->createView(),
            'title' => $this->container->getParameter('planet.newblog.title'),
        );
    }

}