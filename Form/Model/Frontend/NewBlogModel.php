<?php

/**
 * This file is part of the desarrolla2 proyect.
 *
 * Copyright (c)
 * Daniel González Cerviño <daniel.gonzalez@freelancemadrid.es>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Form\Model\Frontend;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class NewBlogModel
 *
 * @author Daniel González <daniel.gonzalez@freelancemadrid.es>
 */
class   NewBlogModel
{

    /**
     * @var string $title
     * @Assert\Length( min=5, max=255 )
     */
    public $title;

    /**
     * @var string $description
     * @Assert\Length( min=5 )
     */
    public $description;

    /**
     * @var string $url
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    public $url;

    /**
     * @var string $rss
     * @Assert\Url()
     */
    public $rss;

    /**
     * @var string $userName
     * @Assert\NotBlank()
     * @Assert\Length( min=3 )
     */
    public $userName;

    /**
     * @var string $userEmail
     * @Assert\NotBlank()
     * @Assert\Email(
     *     message = "'{{ value }}' no es un email válido.",
     *     checkMX = true
     * )
     */
    public $userEmail;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getRss()
    {
        return $this->rss;
    }

    /**
     * @param $rss
     */
    public function setRss($rss)
    {
        $this->rss = $rss;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * @param $userEmail
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
    }
}
