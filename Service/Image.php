<?php
/**
 * This file is part of the planetubuntu project.
 *
 * Copyright (c)
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Service;

use Doctrine\ORM\EntityManager;
use Post

/**
 * Class Image
 *
 * @author Daniel González <daniel.gonzalez@freelancemadrid.es>
 */

class Image extends AbstractService
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager;
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     *
     */
    public function updateImage()
    {
    }
}
