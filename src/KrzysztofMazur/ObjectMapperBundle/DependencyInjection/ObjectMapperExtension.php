<?php
/*
 * This file is part of php-object-mapper.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KrzysztofMazur\ObjectMapperBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * @author Krzysztof Mazur <krz@ychu.pl>
 */
class ObjectMapperExtension extends ConfigurableExtension
{
    /**
     * {@inheritdoc}
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration();
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return 'km_object_mapper';
    }

    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(array(
            __DIR__.'/../Resources/config/',
        )));
        $loader->load('services.xml');

        $container->setParameter('km_object_mapper.configuration_locations', $mergedConfig['configuration_locations']);
        $container
            ->getDefinition('km_object_mapper.mapper.builder')
            ->addMethodCall('setInstantiator', [new Reference($mergedConfig['instantiator_id'])]);
    }
}
