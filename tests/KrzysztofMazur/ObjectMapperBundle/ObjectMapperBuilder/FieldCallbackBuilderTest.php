<?php

namespace Tests\KrzysztofMazur\ObjectMapperBundle\ObjectMapperBuilder;

use KrzysztofMazur\ObjectMapperBundle\ObjectMapperBuilder\FieldCallbackBuilder;
use Tests\KrzysztofMazur\ObjectMapperBundle\ObjectMapperBuilder\Fixtures\Service;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Krzysztof Mazur <krz@ychu.pl>
 */
class FieldCallbackBuilderTest extends TestCase
{
    public function testReplaceCallbacksEmpty()
    {
        $config = [
            'fields' => [
                'target1' => 'new DateTime()',
                'target2' => 'getSomeData()',
                'target3' => 'someData',
                'target4' => 'getSomeDataWithArgs("arg1", "arg2")',
            ],
        ];

        $builder = new FieldCallbackBuilder();

        $builder->replaceCallbacks($config);

        self::assertEquals('new DateTime()', $config['fields']['target1']);
        self::assertEquals('getSomeData()', $config['fields']['target2']);
        self::assertEquals('someData', $config['fields']['target3']);
        self::assertEquals('getSomeDataWithArgs("arg1", "arg2")', $config['fields']['target4']);
    }

    public function testReplaceSuccess()
    {
        $config = [
            'fields' => [
                'target1' => '@serviceId::map',
                'target2' => 'Tests\KrzysztofMazur\ObjectMapperBundle\ObjectMapperBuilder\FieldCallbackBuilderTest::staticMap',
            ],
        ];

        $builder = new FieldCallbackBuilder();
        $service = new Service();
        $container  = $this->createMock(ContainerInterface::class);
        $container->expects(self::once())->method('get')->with(self::equalTo("serviceId"))->willReturn($service);
        $builder->setContainer($container);

        $builder->replaceCallbacks($config);

        self::assertTrue(is_callable($config['fields']['target1']));
        self::assertTrue(is_callable($config['fields']['target2']));

        $config['fields']['target1'](new \stdClass(), new \stdClass());
        $config['fields']['target2'](new \stdClass(), new \stdClass());
    }

    /**
     * @param mixed $source
     * @param mixed $target
     */
    public static function staticMap($source, $target)
    {
    }
}
