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

namespace Desarrolla2\Bundle\PlanetBundle\Model\Test;

/**
 * 
 * Description of Link
 *
 * @author : Daniel González Cerviño <daniel.gonzalez@freelancemadrid.es>  
 * @file : Link.php , UTF-8
 * @date : Mar 26, 2013 , 7:05:39 PM
 */
class Result
{

    protected $name;
    protected $rss;
    protected $items;
    protected $status;
    protected $error;
    protected $time;

    public function __construct()
    {
        $this->status = true;
        $this->items = 0;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setTime($time)
    {
        $this->time = $time;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getRss()
    {
        return $this->rss;
    }

    public function setRss($rss)
    {
        $this->rss = $rss;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setItems($items)
    {
        $this->items = (int) $items;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = (bool) $status;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setError($error)
    {
        $this->error = $error;
    }

}
