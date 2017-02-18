<?php
/*
 * This file is part of php-object-mapper.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KrzysztofMazur\ObjectMapperBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Krzysztof Mazur <krz@ychu.pl>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $tb = new TreeBuilder();

        $tb->root('km_object_mapper', 'array')
            ->children()
                ->scalarNode('instantiator_id')->defaultValue("km_object_mapper.instantiator.doctrine_adapter")->end()
                ->arrayNode('configuration_locations')
                    ->addDefaultsIfNotSet()
                    ->cannotBeEmpty()
                ->end()
            ->end();

        return $tb;
    }
}
