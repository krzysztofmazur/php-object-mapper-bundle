<?php
/*
 * This file is part of php-object-mapper.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KrzysztofMazur\ObjectMapperBundle;

use KrzysztofMazur\ObjectMapperBundle\DependencyInjection\ObjectMapperExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Krzysztof Mazur <krz@ychu.pl>
 */
class ObjectMapperBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new ObjectMapperExtension();
    }
}
