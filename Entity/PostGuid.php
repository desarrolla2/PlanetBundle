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
 * Description of PostGuid
 *
 *
 * @ORM\Table(name="post_guid")
 * @ORM\Entity(repositoryClass="Desarrolla2\Bundle\PlanetBundle\Entity\Repository\PostGuidRepository")
 */
class PostGuid
{

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="guid", type="string", length=255, unique=true)
     */
    protected $guid;

    /**
     *
     * @var Post
     *
     * @ORM\Column(name="post_id", type="integer", unique=true)
     */
    protected $post_id;

    /**
     * @var \DateTime $created_at
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime $updated_at
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * Set post
     *
     * @param \Desarrolla2\Bundle\BlogBundle\Entity\Post $post
     *
     * @return FeedItem
     */
    public function setPost(\Desarrolla2\Bundle\BlogBundle\Entity\Post $post = null)
    {
        $this->post_id = $post->getId();

        return $this;
    }

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
     *
     * @return PostGuid
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
     * Set post_id
     *
     * @param integer $postId
     *
     * @return PostGuid
     */
    public function setPostId($postId)
    {
        $this->post_id = $postId;

        return $this;
    }

    /**
     * Get post_id
     *
     * @return integer
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return PostGuid
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return PostGuid
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
}
