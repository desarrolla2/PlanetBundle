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

namespace Desarrolla2\Bundle\PlanetBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class NewBlogModel {

    /**
     * @var string $title
     * @Assert\MinLength( limit=5 )
     */
    public $title;

    /**
     * @var string $description
     * @Assert\MinLength( limit=5 )
     */
    public $description;

    /**
     * @var string $url
     * @Assert\NotBlank()
     * @Assert\Url()
     * @Assert\MinLength( limit=5 )
     */
    public $url;

    /**
     * @var string $rss
     * @Assert\Url()
     * @Assert\MinLength( limit=5 )
     */
    public $rss;

    /**
     * @var string $userName
     * @Assert\NotBlank()
     * @Assert\MinLength( limit=3 )
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

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getRss() {
        return $this->rss;
    }

    public function setRss($rss) {
        $this->rss = $rss;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function setUserName($userName) {
        $this->userName = $userName;
    }

    public function getUserEmail() {
        return $this->userEmail;
    }

    public function setUserEmail($userEmail) {
        $this->userEmail = $userEmail;
    }

}
