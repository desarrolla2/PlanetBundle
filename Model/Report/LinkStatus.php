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

namespace Desarrolla2\Bundle\PlanetBundle\Model\Report;


/**
 * Class LinkStatus
 *
 * @author Daniel González <daniel.gonzalez@freelancemadrid.es>
 */
class LinkStatus
{

    /**
     * @var
     */
    protected $name;
    /**
     * @var
     */
    protected $rss;
    /**
     * @var int
     */
    protected $items;
    /**
     * @var bool
     */
    protected $status;
    /**
     * @var
     */
    protected $error;
    /**
     * @var
     */
    protected $time;

    /**
     *
     */
    public function __construct()
    {
        $this->status = true;
        $this->items = 0;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
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
     * @return int
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param $items
     */
    public function setItems($items)
    {
        $this->items = (int)$items;
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     */
    public function setStatus($status)
    {
        $this->status = (bool)$status;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }
}
