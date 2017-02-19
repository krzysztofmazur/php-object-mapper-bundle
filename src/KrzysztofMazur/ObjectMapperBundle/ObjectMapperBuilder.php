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
use KrzysztofMazur\ObjectMapperBundle\ObjectMapperBuilder\ConfigurationLoaderInterface;

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
     * @return ObjectMapper
     */
    public function build()
    {
        return ObjectMapperSimpleBuilder::create()
            ->setInstantiator($this->instantiator)
            ->setConfig($this->configurationLoader->load())
            ->build();
    }
}
