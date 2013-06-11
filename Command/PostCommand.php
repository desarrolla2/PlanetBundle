<?php

/**
 * This file is part of the planetubuntu proyect.
 * 
 * Copyright (c)
 * Daniel González <daniel.gonzalez@freelancemadrid.es> 
 * 
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 
 * Description of PostCommand
 *
 * @author : Daniel González <daniel.gonzalez@freelancemadrid.es> 
 * @file : PostCommand.php , UTF-8
 * @date : May 18, 2013 , 2:31:41 AM
 */
class PostCommand extends ContainerAwareCommand
{

    protected $output;
    protected $input;

    /**
     * @access protected
     * @return void
     */
    protected function configure()
    {
        $this
                ->setName('planet:post:execute')
                ->setDescription('Not ready yet')
        ;
    }

    /**
     * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @param InputInterface  $input  Inpunt arguments
     * @param OutputInterface $output Output stream
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $handler = $this->getContainer()->get('planet.post.handler');
        $handler->publishOne();
    }

}