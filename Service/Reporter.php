<?php
/**
 * This file is part of the planetandroid project.
 *
 * Copyright (c)
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Service;

use Doctrine\ORM\EntityManager;
use Desarrolla2\Bundle\BlogBundle\Entity\Link;
use Desarrolla2\Bundle\PlanetBundle\Model\Report\Link as ReportLink;

/**
 * Class Report
 *
 * @author Daniel González <daniel.gonzalez@freelancemadrid.es>
 */

class Reporter
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager;
     */
    protected $em;

    /**
     *
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getLinksStatistics()
    {
        $result = array();
        $links = $this->getLinks();
        foreach ($links as $link) {
            $item = new ReportLink();
            $item->setName($link->getName());
            $item->setUrl($link->getUrl());
            $result[] = $link;
        }

        return $result;
    }

    /**
     *
     * @return array
     */
    protected function getLinks()
    {
        $links = $this->em->getRepository('BlogBundle:Link')->getActive();

        return $links;
    }
}