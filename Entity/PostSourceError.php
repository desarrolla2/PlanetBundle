<?php
/**
 * This file is part of the planetubuntu project.
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
 * Class PostSourceError
 *
 * @author Daniel González <daniel.gonzalez@freelancemadrid.es>
 * @ORM\Table(name="post_source_error")
 * @ORM\Entity(repositoryClass="Desarrolla2\Bundle\PlanetBundle\Entity\Repository\PostSourceErrorRepository")
 */
class PostSourceError
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
     * @var string $source
     *
     * @ORM\Column(name="source", type="string", length=255)
     */
    protected $source = '';

    /**
     * @var integer errors
     *
     * @ORM\Column(name="errors", type="integer")
     */
    private $errors = 0;

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
     * @param int $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return int
     */
    public function getErrors()
    {
        return $this->errors;
    }

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
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Increase numner errors
     */
    public function increase()
    {
        return $this->errors++;
    }
}
