<?php


namespace Desarrolla2\Bundle\PlanetBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/report")
 */
class ReportController  extends  Controller {

    /**
     * @Route("/items", name="_planet_report_items")
     * @Template()
     */
    public function itemsAction(){

        die('1');

    }

}