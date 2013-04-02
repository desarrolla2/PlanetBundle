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

namespace Desarrolla2\Bundle\PlanetBundle\Form\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Desarrolla2\Bundle\PlanetBundle\Handler\NewBlog;

/**
 * 
 * Description of NewBlogHandler
 *
 * @author : Daniel González Cerviño <daniel.gonzalez@freelancemadrid.es>  
 * @file : NewBlogHandler.php , UTF-8
 * @date : Mar 5, 2013 , 4:44:27 PM
 */ 
class NewBlogHandler {

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \Desarrolla2\Bundle\PlanetBundle\Form\Type\NewBlogType
     */
    protected $form;

    /**
     * @var Desarrolla2\Bundle\PlanetBundle\Form\Handler\NewBlogHandler 
     */
    protected $handler;

    public function __construct(Request $request, Form $form, NewBlog $handler) {
        $this->request = $request;
        $this->form = $form;
        $this->handler = $handler;
    }

    /**
     * Process forn
     */
    public function process() {
        $this->form->bind($this->request);
        if ($this->form->isValid()) {
            $this->handler->send($this->form->getData());
            return true;
        }
        return false;
    }

}
