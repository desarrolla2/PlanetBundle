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
namespace Desarrolla2\Bundle\PlanetBundle\Controller\Frontend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * 
 * Description of TestController
 *
 */
class TestLinkController extends Controller {

    /**
     *
     * @Route("/test-links", name="_planet_link_test")
     * @Template()
     */
    public function indexAction(Request $request) {

        $client = $this->container->get('planet.client.test');
        $results = $client->run();
        return array(
            'results' => $results,
        );
    }

}

