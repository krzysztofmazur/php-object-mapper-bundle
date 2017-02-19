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
class PhpConfigurationLoader implements ConfigurationLoaderInterface
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
            foreach (glob($directory."/*.php") as $file) {
                $configs[] = self::includeFile($file);
            }
        }

        return $configs;
    }

    /**
     * @param string $file
     * @return array
     */
    private static function includeFile($file)
    {
        return include $file;
    }
}
