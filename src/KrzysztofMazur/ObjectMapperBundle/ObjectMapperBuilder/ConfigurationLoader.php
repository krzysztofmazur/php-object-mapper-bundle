<?php
/*
 * This file is part of php-object-mapper.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KrzysztofMazur\ObjectMapperBundle\ObjectMapperBuilder;

/**
 * @author Krzysztof Mazur <krz@ychu.pl>
 */
class ConfigurationLoader implements ConfigurationLoaderInterface
{
    /**
     * @var array
     */
    private static $loaderClasses = [
        'yml' => YamlConfigurationLoader::class,
        'xml' => XmlConfigurationLoader::class,
        'php' => PhpConfigurationLoader::class,
    ];

    /**
     * @var FieldCallbackBuilder
     */
    private $fieldCallbackBuilder;

    /**
     * @var array
     */
    private $loaders;

    /**
     * @param array                $config
     * @param FieldCallbackBuilder $fieldCallbackBuilder
     */
    public function __construct(array $config, FieldCallbackBuilder $fieldCallbackBuilder)
    {
        $this->fieldCallbackBuilder = $fieldCallbackBuilder;
        $this->loaders = [];
        foreach (['php', 'xml', 'yml'] as $type) {
            $this->loaders[] = self::createLoader($config, $type);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $configs = [];
        foreach ($this->loaders as $loader) {
            $configs = array_merge($configs, $loader->load());
        }
        foreach ($configs as $config) {
            $this->fieldCallbackBuilder->replaceCallbacks($config);
        }

        return $configs;
    }

    /**
     * @param array  $config
     * @param string $type
     * @return ConfigurationLoaderInterface
     */
    private static function createLoader(array $config, $type)
    {
        $filtered = array_filter($config, function ($item) use ($type) {
            return $type === $item['type'];
        });
        $loaderClass = self::$loaderClasses[$type];

        return new $loaderClass($filtered);
    }
}
