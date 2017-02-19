<?php

namespace KrzysztofMazur\ObjectMapperBundle\ObjectMapperBuilder;

class ComposeConfigurationLoader implements ConfigurationLoaderInterface
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
     * @var array
     */
    private $loaders;

    public function __construct(array $config)
    {
        $this->loaders = [];
        foreach (['php', 'xml', 'yml'] as $type) {
            $this->loaders[] = self::createLoader($config, $type);
        }
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

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $configs = [];
        foreach ($this->loaders as $loader) {
            $configs = array_merge($configs, $loader->load());
        }

        return $configs;
    }
}
