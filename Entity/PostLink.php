<?php
/**
 * This file is part of the planetandroid project.
 *
 * Copyright (c)
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 *
 * Class PostLink
 *
 *
 * @author Daniel González <daniel.gonzalez@freelancemadrid.es>
 * @ORM\Table(name="post_link")
 * @ORM\Entity(repositoryClass="Desarrolla2\Bundle\PlanetBundle\Entity\Repository\PostLinkRepository")
 */
class PostLink
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @var Post
     *
     * @ORM\OneToOne(targetEntity="Desarrolla2\Bundle\BlogBundle\Entity\Post")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $post;

    /**
     *
     * @var Link
     *
     * @ORM\ManyToOne(targetEntity="Desarrolla2\Bundle\BlogBundle\Entity\Link")
     * @ORM\JoinColumn(name="link_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $link;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime $updated_at
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Desarrolla2\Bundle\PlanetBundle\Entity\Link $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return \Desarrolla2\Bundle\PlanetBundle\Entity\Link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param \Desarrolla2\Bundle\PlanetBundle\Entity\Post $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    /**
     * @return \Desarrolla2\Bundle\PlanetBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param \DateTime $publishedAt
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }
    

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PostLink
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
     * @return PostLink
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
