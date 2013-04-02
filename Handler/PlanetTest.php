<?php

/**
 * This file is part of the planetubuntu proyect.
 * 
 * Copyright (c)
 * Daniel González Cerviño <daniel.gonzalez@externos.seap.minhap.es>  
 * 
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Handler;

use Desarrolla2\RSSClient\RSSClientInterface;
Use Doctrine\ORM\EntityManager;
use Desarrolla2\Bundle\PlanetBundle\Model\Test\Result;
use Desarrolla2\Bundle\BlogBundle\Entity\Link;
use Desarrolla2\Timer\Timer;

/**
 * 
 * Description of PlanetTest
 *
 * @author : Daniel González Cerviño <daniel.gonzalez@externos.seap.minhap.es>  
 * @file : PlanetTest.php , UTF-8
 * @date : Mar 27, 2013 , 11:18:53 AM
 */
class PlanetTest {

    /**
     *
     * @var \Doctrine\ORM\EntityManager; 
     */
    protected $em;

    /**
     * @var \Desarrolla2\RSSClient\RSSClientInterface 
     */
    protected $client;


    /**
     * 
     * @param \Doctrine\ORM\EntityManager $em
     * @param \Desarrolla2\RSSClient\RSSClientInterface $client
     */
    public function __construct(EntityManager $em, RSSClientInterface $client) {
        $this->em = $em;
        $this->client = $client;
    }

    /**
     * 
     */
    public function run() {
        $results = array();
        $links = $this->getLinks();
        foreach ($links as $link) {
            if ($link->getRSS()) {
                $time_start = microtime(true);
                $result = $this->createNewResult($link);
                $this->client->clearErrors();
                $this->client->setFeed($link->getRSS());
                try {
                    $feeds = $this->client->fetch();
                    if ($feeds) {
                        if ($feeds->count()) {
                            $result->setItems($feeds->count());
                        }
                    }
                    if ($this->client->hasErrors()) {
                        $result->setStatus(false);
                        $result->setError($this->client->getLastError());
                    }
                } catch (\Exception $e) {
                    $result->setStatus(false);
                    $result->setError($e->getMessage());
                }
                $result->setTime(microtime(true) - $time_start);
                $results[] = $result;
            }
        }
        return $results;
    }

    protected function createNewResult(Link $link) {
        $result = new Result();
        $result->setName($link->getName());
        $result->setRSS($link->getRSS());
        return $result;
    }

    /**
     * 
     * @return 
     */
    protected function getLinks() {
        $links = $this->em->getRepository('BlogBundle:Link')->getActive();
        return $links;
    }

}