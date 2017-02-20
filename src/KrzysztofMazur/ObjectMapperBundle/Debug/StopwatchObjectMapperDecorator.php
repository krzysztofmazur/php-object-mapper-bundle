<?php

namespace KrzysztofMazur\ObjectMapperBundle\Debug;

use KrzysztofMazur\ObjectMapper\Exception\MappingException;
use KrzysztofMazur\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * @author Krzysztof Mazur <krz@ychu.pl>
 */
class StopwatchObjectMapperDecorator implements ObjectMapperInterface
{
    /**
     * @var ObjectMapperInterface
     */
    private $mapper;

    /**
     * @var Stopwatch
     */
    private $stopwatch;

    /**
     * @param ObjectMapperInterface $mapper
     * @param Stopwatch             $stopwatch
     */
    public function __construct(ObjectMapperInterface $mapper, Stopwatch $stopwatch)
    {
        $this->mapper = $mapper;
        $this->stopwatch = $stopwatch;
    }

    /**
     * {@inheritdoc}
     */
    public function map($source, $targetClass, $mapId = null)
    {
        $this->stopwatch->start('ObjectMapper::map');
        $result = $this->mapper->map($source, $targetClass, $mapId);
        $this->stopwatch->stop('ObjectMapper::map');

        return $result;
    }

    /**
     * @param mixed  $source
     * @param mixed  $target
     * @param string $mapId
     * @return mixed
     * @throws MappingException
     */
    public function mapToObject($source, $target, $mapId = null)
    {
        $this->stopwatch->start('ObjectMapper::mapToObject');
        $result = $this->mapper->mapToObject($source, $target, $mapId);
        $this->stopwatch->stop('ObjectMapper::mapToObject');

        return $result;
    }
}
