<?php
/*
 * This file is part of php-object-mapper.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KrzysztofMazur\ObjectMapperBundle;

use KrzysztofMazur\ObjectMapper\Builder\ObjectMapperSimpleBuilder;
use KrzysztofMazur\ObjectMapper\ObjectMapper;
use KrzysztofMazur\ObjectMapper\Util\InstantiatorInterface;
use KrzysztofMazur\ObjectMapperBundle\Debug\StopwatchObjectMapperDecorator;
use KrzysztofMazur\ObjectMapperBundle\ObjectMapperBuilder\ConfigurationLoaderInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * @author Krzysztof Mazur <krz@ychu.pl>
 */
class ObjectMapperBuilder
{
    /**
     * @var ConfigurationLoaderInterface
     */
    private $configurationLoader;

    /**
     * @var InstantiatorInterface
     */
    private $instantiator;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @var Stopwatch
     */
    private $stopwatch;

    /**
     * @param ConfigurationLoaderInterface $configurationLoader
     * @return $this
     */
    public function setConfigurationLoader(ConfigurationLoaderInterface $configurationLoader)
    {
        $this->configurationLoader = $configurationLoader;

        return $this;
    }

    /**
     * @param InstantiatorInterface $instantiator
     * @return $this
     */
    public function setInstantiator(InstantiatorInterface $instantiator)
    {
        $this->instantiator = $instantiator;

        return $this;
    }

    /**
     * @param bool $debug
     * @return $this
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * @param Stopwatch $stopwatch
     * @return $this
     */
    public function setStopwatch($stopwatch)
    {
        $this->stopwatch = $stopwatch;

        return $this;
    }

    /**
     * @return ObjectMapper
     */
    public function build()
    {
        if ($this->debug) {
            $this->stopwatch->start('ObjectMapperBuilder::build');
        }

        $mapper = ObjectMapperSimpleBuilder::create()
            ->setInstantiator($this->instantiator)
            ->setConfig($this->configurationLoader->load())
            ->build();

        if ($this->debug) {
            $mapper = new StopwatchObjectMapperDecorator($mapper, $this->stopwatch);
            $this->stopwatch->stop('ObjectMapperBuilder::build');
        }

        return $mapper;
    }
}
