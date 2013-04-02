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

namespace Desarrolla2\Bundle\PlanetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * 
 * Description of FeedItem
 *
 *
 * @ORM\Table(name="post_guid")
 * @ORM\Entity(repositoryClass="Desarrolla2\Bundle\PlanetBundle\Entity\Repository\PostGuidRepository")
 */
class PostGuid {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="guid", type="string", length=255, unique=true)
     */
    private $guid;

    /**
     * 
     * @var Post
     * 
     * @ORM\ManyToOne(targetEntity="Desarrolla2\Bundle\BlogBundle\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;

    /**
     * @var \DateTime $updated_at
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var \DateTime $published_at
     *
     * @ORM\Column(name="published_at", type="datetime", nullable=true)
     */
    private $publishedAt;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set guid
     *
     * @param string $guid
     * @return FeedItem
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid
     *
     * @return string 
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return FeedItem
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     * @return FeedItem
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime 
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set post
     *
     * @param \Desarrolla2\Bundle\BlogBundle\Entity\Post $post
     * @return FeedItem
     */
    public function setPost(\Desarrolla2\Bundle\BlogBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \Desarrolla2\Bundle\BlogBundle\Entity\Post 
     */
    public function getPost()
    {
        return $this->post;
    }
}