<?php
/**
 * This file is part of the planetubuntu project.
 *
 * Copyright (c)
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Service;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Class AbstractService
 *
 * @author Daniel González <daniel.gonzalez@freelancemadrid.es>
 */

abstract class AbstractService
{

    /**
     * @var LoggerInterface;
     */
    protected $logger;

    /**
     * @param string $message
     * @param string $logLevel
     * @param array  $context
     */
    protected function notify($message, $logLevel = LogLevel::NOTICE, $context = array())
    {
        $this->logger->log($logLevel, '[spider] ' . $message, $context);
    }
}
