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
use Desarrolla2\Bundle\PlanetBundle\Form\Type\UnrelatedType;
use Desarrolla2\Bundle\PlanetBundle\Form\Model\UnrelatedModel;
use Desarrolla2\Bundle\PlanetBundle\Form\Handler\UnrelatedHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * 
 * Description of UnrelatedController
 */
class UnrelatedController extends Controller
{

    /**
     * @Route("/unrelated-post/{slug}" , name="_unrelated", requirements={"slug" = "[\w\d\-]+"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $post = $this->getDoctrine()->getManager()
                        ->getRepository('BlogBundle:Post')->getOneBySlug($request->get('slug', false));
        if (!$post) {
            throw $this->createNotFoundException('The post does not exist');
        }

        $form = $this->createForm(new UnrelatedType(), new UnrelatedModel());
        if ($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getEntityManager();
            $handler = new UnrelatedHandler($em, $request, $form, $post);
            if ($handler->process()) {
                $this->get('session')
                        ->getFlashBag()
                        ->add('success', 'Hemos recibido su mensaje');
                return new RedirectResponse($this->generateUrl('_message'), 302);
            }
        }
        return array(
            'post' => $post,
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/resume/unrelated" , name="_unrelated_resume")
     * @Template()
     */
    public function resumeAction()
    {
        $unrelated =
                $this->getDoctrine()->getManager()
                ->getRepository('PlanetBundle:Unrelated')
                ->getPublished();
        return array(
            'unrelated' => $unrelated,
        );
    }

}