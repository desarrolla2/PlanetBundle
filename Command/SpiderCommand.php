<?php
/**
 * This file is part of the planetandroid project.
 *
 * Copyright (c)
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SpiderCommand
 *
 * @author Daniel González <daniel.gonzalez@freelancemadrid.es>
 */

class SpiderCommand extends ContainerAwareCommand
{

    /**
     * @var InputInterface
     */
    protected $output;

    /**
     * @var OutputInterface
     */
    protected $input;

    /**
     * @access protected
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('planet:spider:execute')
            ->setDescription('Retrieve All Active Items and find for new elements');
    }

    /**
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->getContainer()->get('planet.spider');
        $client->run();
    }
}
