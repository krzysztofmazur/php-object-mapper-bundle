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
interface ConfigurationLoaderInterface
{
    /**
     * @return array
     */
    public function load();
}
