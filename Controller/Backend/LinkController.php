<?php
/**
 * This file is part of the planetubuntu project.
 *
 * Copyright (c)
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class LinkController
 *
 * @author Daniel González <daniel.gonzalez@freelancemadrid.es>
 */

class LinkController
{
    /**
     * @Route("/link" , name="_planet_backend_unrelated")
     * @Template()
     */
    public function indexAction()
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
