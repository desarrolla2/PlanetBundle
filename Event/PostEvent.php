<?php
/**
 * This file is part of the planetandroid project.
 *
 * Copyright (c)
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Desarrolla2\Bundle\BlogBundle\Entity\Post;


/**
 * Class PostEvent
 *
 * @author Daniel González <daniel.gonzalez@freelancemadrid.es>
 */

class PostEvent extends Event
{

    /**
     * @var
     */
    protected $post;

    /**
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }
}