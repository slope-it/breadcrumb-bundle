<?php

namespace SlopeIt\Tests\BreadcrumbBundle\Annotation;

use SlopeIt\BreadcrumbBundle\Annotation\Breadcrumb;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \SlopeIt\BreadcrumbBundle\Annotation\Breadcrumb
 */
class BreadcrumbTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * The constructor can also receive an array of breadcrumb items. When used as annotation, its parameter is passed
     * via an array with the 'value' key.
     *
     * @covers ::__construct
     */
    public function test_constructor_asAnnotation_withMultipleItems()
    {
        $annotation = new Breadcrumb([
            'value' => [
                ['label' => 'aLabel', 'route' => 'aRoute', 'params' => ['param' => 'value']],
                ['label' => 'anotherLabel', 'route' => 'anotherRoute']
            ]
        ]);

        $this->assertEquals('aLabel', $annotation->items[0]['label']);
        $this->assertEquals('aRoute', $annotation->items[0]['route']);
        $this->assertEquals(['param' => 'value'], $annotation->items[0]['params']);
        $this->assertEquals('anotherLabel', $annotation->items[1]['label']);
        $this->assertEquals('anotherRoute', $annotation->items[1]['route']);
        $this->assertArrayNotHasKey('params', $annotation->items[1]);
    }

    /**
     * The constructor can receive a single breadcrumb item. When used as annotation, its parameter is passed via an
     * array with the 'value' key.
     *
     * @covers ::__construct
     */
    public function test_constructor_asAnnotation_withSingleItem()
    {
        $annotation = new Breadcrumb([
            'value' => [
                'label' => 'aLabel',
                'route' => 'aRoute'
            ]
        ]);

        $this->assertEquals('aLabel', $annotation->items[0]['label']);
        $this->assertEquals('aRoute', $annotation->items[0]['route']);
    }

    /**
     * The constructor can also receive an array of breadcrumb items.
     *
     * @covers ::__construct
     */
    public function test_constructor_asAttribute_withMultipleItems()
    {
        $annotation = new Breadcrumb([
            ['label' => 'aLabel', 'route' => 'aRoute', 'params' => ['param' => 'value']],
            ['label' => 'anotherLabel', 'route' => 'anotherRoute']
        ]);

        $this->assertEquals('aLabel', $annotation->items[0]['label']);
        $this->assertEquals('aRoute', $annotation->items[0]['route']);
        $this->assertEquals(['param' => 'value'], $annotation->items[0]['params']);
        $this->assertEquals('anotherLabel', $annotation->items[1]['label']);
        $this->assertEquals('anotherRoute', $annotation->items[1]['route']);
        $this->assertArrayNotHasKey('params', $annotation->items[1]);
    }

    /**
     * The annotation constructor can receive a single breadcrumb item.
     *
     * @covers ::__construct
     */
    public function test_constructor_asAttribute_withSingleItem()
    {
        $annotation = new Breadcrumb([
            'label' => 'aLabel',
            'route' => 'aRoute'
        ]);

        $this->assertEquals('aLabel', $annotation->items[0]['label']);
        $this->assertEquals('aRoute', $annotation->items[0]['route']);
    }
}
