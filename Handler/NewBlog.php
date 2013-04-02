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

namespace Desarrolla2\Bundle\PlanetBundle\Handler;

use \Desarrolla2\Bundle\PlanetBundle\Form\Model\NewBlogModel;
use \Symfony\Bundle\TwigBundle\TwigEngine;
use \Swift_Message;
use \Swift_Mailer;

/**
 * 
 * Description of NewBlog
 *
 * @author : Daniel González Cerviño <daniel.gonzalez@freelancemadrid.es>  
 * @file : NewBlog.php , UTF-8
 * @date : Mar 5, 2013 , 4:53:44 PM
 */
class NewBlog {

    /**
     * @var \Swift_Mailer 
     */
    protected $mailer;

    /**
     * @var \Symfony\Bundle\TwigBundle\TwigEngine 
     */
    protected $templating;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $to;

    /**
     * 
     * @param \Swift_Mailer $mailer
     * @param \Symfony\Bundle\TwigBundle\TwigEngine $templating
     * @param string $subjet
     * @param string $from
     * @param string $to
     */
    public function __construct(Swift_Mailer $mailer, TwigEngine $templating, $subjet, $to) {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->subject = $subjet;
        $this->to = $to;
    }

    /**
     * @param \Desarrolla2\Bundle\PlanetBundle\Form\Model\NewBlogModel $data
     */
    public function send(NewBlogModel $data) {
        $body = $this->renderTemplate($data);
        $message = $this->getMessage();
        $message->setTo($this->to);
        $message->setFrom($this->to);
        $message->setSubject($this->subject);
        $message->setBody($body, 'text/html');
        $message->setReplyTo($data->getUserEmail(),  $data->getUserName());
        return $this->mailer->send($message);
    }

    /**
     * 
     * @return \Swift_Message
     */
    protected function getMessage() {
        return Swift_Message::newInstance();
    }

    /**
     * 
     * @param \Desarrolla2\Bundle\PlanetBundle\Form\Model\NewBlogModel $data
     */
    protected function renderTemplate(NewBlogModel $data) {
        return $this->templating->render('PlanetBundle:NewBlog:email.html.twig', array(
                    'subject' => $this->subject,
                    'email' => $data->getUserEmail(),
                    'name' => $data->getUserName(),
                    'title' => $data->getTitle(),
                    'description' => $data->getDescription(),
                    'url' => $data->getUrl(),
                    'rss' => $data->getRss(),
        ));
    }

}
