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

use FastFeed\FastFeedInterface;
use Doctrine\ORM\EntityManager;
use Desarrolla2\Bundle\BlogBundle\Entity\Link;
use Desarrolla2\Bundle\PlanetBundle\Model\Report\LinksStatistics;
use Desarrolla2\Bundle\PlanetBundle\Model\Report\LinkStatus;
use Desarrolla2\Cache\Cache;
use DateTime;

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
     * @var FastFeedInterface
     */
    protected $client;

    /**
     * @var \Desarrolla2\Cache\Cache
     */
    protected $cache;

    /**
     * @param EntityManager     $em
     * @param FastFeedInterface $client
     * @param Cache             $cache
     */
    public function __construct(EntityManager $em, FastFeedInterface $client, Cache $cache)
    {
        $this->em = $em;
        $this->client = $client;
        $this->cache = $cache;
    }

    public function getLinksStatus()
    {
        $result = $this->cache->get('planet.reporter.link.status');
        if ($result) {
            return $result;
        }
        $results = array();
        $links = $this->getActiveLinks();
        foreach ($links as $link) {
            if ($link->getRSS()) {
                $time_start = microtime(true);
                $result = $this->createLinkStatus($link);
                $this->client->setFeed($link->getName(), $link->getRSS());
                try {
                    $feeds = $this->client->fetch($link->getName());
                    if ($feeds) {
                        if (count($feeds)) {
                            $result->setItems(count($feeds));
                        }
                    }
                } catch (\Exception $e) {
                    $result->setStatus(false);
                    $result->setError($e->getMessage());
                }
                $result->setTime(microtime(true) - $time_start);
                $results[] = $result;
            }
        }

        $this->cache->set('planet.reporter.link.status', $results, 3600 * 24);

        return $results;
    }

    public function getLinksStatistics()
    {
        $result = $this->cache->get('planet.reporter.link.statistics');
        if ($result) {
            return $result;
        }
        $result = array();
        $links = $this->getActiveLinks();
        $now = new DateTime();
        foreach ($links as $link) {
            $item = new LinksStatistics();
            $item->setName($link->getName());
            $item->setUrl($link->getUrl());
            $months = array();
            for ($month = 0; $month < 6; $month++) {
                $from = clone $now;
                $to = clone $now;
                $from->modify('-' . ($month + 1) . ' months');
                $to->modify('-' . ($month) . ' months');
                $months[] = $this->em->getRepository('PlanetBundle:PostLink')->countFromTo($link, $from, $to);
            }
            $item->setMonths($months);
            $result[] = $item;
        }

        $this->cache->set('planet.reporter.link.statistics', $result, 3600 * 24);

        return $result;
    }

    /**
     *
     * @return array
     */
    protected function getActiveLinks()
    {
        $links = $this->em->getRepository('BlogBundle:Link')->getActiveOrdered();

        return $links;
    }

    /**
     * @param Link $link
     *
     * @return LinkStatus
     */
    protected function createLinkStatus(Link $link)
    {
        $result = new LinkStatus();
        $result->setName($link->getName());
        $result->setRSS($link->getRSS());

        return $result;
    }
}
