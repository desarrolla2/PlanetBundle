<?php

/**
 * This file is part of the planetubuntu proyect.
 * 
 * Copyright (c)
 * Daniel González Cerviño <daniel.gonzalez@externos.seap.minhap.es>  
 * 
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace Desarrolla2\Bundle\PlanetBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * 
 * Description of PlanetTestCommand
 *
 * @author : Daniel González Cerviño <daniel.gonzalez@externos.seap.minhap.es>  
 * @file : PlanetTestCommand.php , UTF-8
 * @date : Mar 27, 2013 , 12:31:44 PM
 */
class PlanetTestCommand extends ContainerAwareCommand {

    protected $output;
    protected $input;

    /**
     * @access protected
     * @return void
     */
    protected function configure() {
        $this
                ->setName('planet:test')
                ->setDescription('Not ready yet')
        ;
    }

    /**
     * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output) {
        parent::initialize($input, $output);
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @param InputInterface  $input  Inpunt arguments
     * @param OutputInterface $output Output stream
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $client = $this->getContainer()->get('planet.client.test');
        $result = $client->run();
        var_dump($result);
    }

}