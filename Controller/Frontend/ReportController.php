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
 *
 * @Route("/report")
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


    /**
     *
     * @Route("/link", name="_planet_report_link_list")
     * @Template()
     */
    public function linkAction()
    {
        return array(
            'links' =>
                $this->getDoctrine()->getManager()
                    ->getRepository('BlogBundle:Link')->getActiveOrdered()
        );
    }

    /**
     *
     * @Route(
     *      "/link/{slug}/{page}",
     *      name="_planet_report_link_items",
     *      requirements={"slug"="[\w\d\-]+", "page"="\d{1,6}"},
     *      defaults={"page" = "1"}
     * )
     * @Template()
     */
    public function linkViewAction(Request $request)
    {
        $paginator = $this->get('knp_paginator');

        $link = $this->getDoctrine()->getManager()
            ->getRepository('BlogBundle:Link')->getOneBySlug($request->get('slug', false));

        if (!$link) {
            throw $this->createNotFoundException('Link not found');
        }

        $query = $this->getDoctrine()->getManager()
            ->getRepository('PlanetBundle:PostLink')->getQueryForGetByLink($link);

        try {
            $pagination = $paginator->paginate(
                $query,
                $this->getPage(),
                $this->container->getParameter('blog.items')
            );
        } catch (QueryException $e) {
            throw $this->createNotFoundException('Page not found');
        }

        return array(
            'link' => $link,
            'page' => $this->getPage(),
            'pagination' => $pagination,
        );
    }

    /**
     * @return int
     */
    protected function getPage()
    {
        $request = $this->getRequest();
        $page = (int)$request->get('page', 1);
        if ($page < 1) {
            $this->createNotFoundException('Page number is not valid' . $page);
        }

        return $page;
    }
}