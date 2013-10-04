<?php
/**
 * This file is part of the planetandroid project.
 *
 * Copyright (c)
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Controller\Frontend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Desarrolla2\Bundle\PlanetBundle\Service\Reporter;

/**
 * Class ReportController
 *
 * @author Daniel González <daniel.gonzalez@freelancemadrid.es>
 */

/**
 * @Route("/report")
 * @Template()
 */
class ReportController extends Controller
{
    /**
     * @var \Desarrolla2\Bundle\PlanetBundle\Service\Reporter
     */
    protected $reporter;

    /**
     * @Route("", name="_planet_report")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/links", name="_planet_report_links")
     * @Template()
     */
    public function linksAction()
    {
        $this->reporter = $this->container->get('planet.reporter');
        $results = $this->reporter->getLinksStatistics();

        return array(
            'results' => $results,
        );
    }

    /**
     *
     * @Route("/test-links", name="_planet_report_links_test")
     * @Template()
     */
    public function testLinksAction(Request $request)
    {

        $client = $this->container->get('planet.reporter');
        $results = $client->getLinksStatus();

        return array(
            'results' => $results,
        );
    }
}