<?php

namespace KrzysztofMazur\ObjectMapperBundle\ObjectMapperBuilder;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Krzysztof Mazur <krz@ychu.pl>
 */
class FieldCallbackBuilder implements ContainerAwareInterface
{
    const SERVICE_CALLBACK_PATTERN = '/^@(.*):{2}(.*)$/';
    const STATIC_CALLBACK_PATTERN = '/^(.*):{2}(.*)$/';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param array $config
     */
    public function replaceCallbacks(array &$config)
    {
        foreach ($config['fields'] as $target => $source) {
            if (is_callable($source)) {
                continue;
            }
            if (preg_match(self::SERVICE_CALLBACK_PATTERN, $source, $matches)) {
                $config['fields'][$target] = $this->parseServiceCallback($matches);
            } elseif (preg_match(self::STATIC_CALLBACK_PATTERN, $source, $matches)) {
                $config['fields'][$target] = $this->parseStaticCallback($matches);
            }
        }
    }

    /**
     * @param array $matches
     * @return callable
     */
    private function parseServiceCallback($matches)
    {
        $serviceId = $matches[1];
        $method = $matches[2];
        $container = $this->container;

        return function ($source, $target) use ($container, $serviceId, $method) {
            $container->get($serviceId)->{$method}($source, $target);
        };
    }

    /**
     * @param array $matches
     * @return callable
     */
    private function parseStaticCallback($matches)
    {
        $className = $matches[1];
        $method = $matches[2];

        return function ($source, $target) use ($className, $method) {
            $className::$method($source, $target);
        };
    }
}
