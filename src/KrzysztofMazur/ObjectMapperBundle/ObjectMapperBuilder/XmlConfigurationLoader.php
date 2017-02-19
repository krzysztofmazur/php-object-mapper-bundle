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
class XmlConfigurationLoader implements ConfigurationLoaderInterface
{
    /**
     * @var string[]
     */
    private $directories;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->directories = array_map(function ($item) {
            return $item['path'];
        }, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $configs = [];
        foreach ($this->directories as $directory) {
            foreach (glob($directory."/*.xml") as $file) {
                $configs[] = self::parseConfigurationFile(file_get_contents($file));
            }
        }

        return $configs;
    }

    private static function parseConfigurationFile($xml)
    {
        $config = (array)simplexml_load_string($xml);
        $parsed = [];
        self::parseStringValue($parsed, $config, 'from');
        self::parseStringValue($parsed, $config, 'to');
        self::parseBooleanValue($parsed, $config, 'auto');
        self::parseArrayValue($parsed, $config, 'fields');

        return $parsed;
    }

    /**
     * @param array  $parsed
     * @param array  $config
     * @param string $property
     */
    private static function parseStringValue(&$parsed, $config, $property)
    {
        if (isset($config[$property])) {
            $parsed[$property] = $config[$property];
        }
    }

    /**
     * @param array  $parsed
     * @param array  $config
     * @param string $property
     */
    private static function parseBooleanValue(&$parsed, $config, $property)
    {
        if (isset($config[$property])) {
            $parsed[$property] = $config[$property] === "true";
        }
    }

    /**
     * @param array  $parsed
     * @param array  $config
     * @param string $property
     */
    private static function parseArrayValue(&$parsed, $config, $property)
    {
        if (isset($config[$property])) {
            $parsed[$property] = (array)$config[$property];
        }
    }
}
