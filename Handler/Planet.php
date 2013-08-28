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

namespace Desarrolla2\Bundle\PlanetBundle\Handler;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Desarrolla2\Bundle\BlogBundle\Entity\Post;
use Desarrolla2\Bundle\BlogBundle\Entity\Author;
use Desarrolla2\Bundle\BlogBundle\Entity\Link;
use Desarrolla2\Bundle\BlogBundle\Model\PostStatus;
use Desarrolla2\Bundle\PlanetBundle\Entity\PostGuid;
use Desarrolla2\Bundle\PlanetBundle\Entity\LinkPost;
use Desarrolla2\Bundle\PlanetBundle\Helper\PostHelper;
use Desarrolla2\Bundle\PlanetBundle\Event\PostEvent;
use Desarrolla2\Bundle\PlanetBundle\Event\PostEvents;
use Desarrolla2\RSSClient\RSSClientInterface;
use Desarrolla2\RSSClient\Node\Node;
use \DOMDocument;
use \DateTime;

/**
 *
 * Description of Planet
 *
 * @author : Daniel González Cerviño <daniel.gonzalez@freelancemadrid.es>
 */
class Planet
{



    /**
     *
     * @param \Doctrine\ORM\EntityManager                        $em
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     * @param \Desarrolla2\RSSClient\RSSClientInterface          $client
     */
    public function __construct(EntityManager $em, EventDispatcher $dispatcher, RSSClientInterface $client)
    {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
        $this->client = $client;
    }


}
