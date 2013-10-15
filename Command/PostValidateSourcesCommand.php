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
 * @file   : PostCommand.php , UTF-8
 * @date   : May 18, 2013 , 2:31:41 AM
 */
class PostValidateSourcesCommand extends ContainerAwareCommand
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
            ->setName('planet:post:sources:validate')
            ->setDescription('Not ready yet');
    }

    /**
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $handler = $this->getContainer()->get('planet.post.handler');
        $handler->validateSources();
    }
}