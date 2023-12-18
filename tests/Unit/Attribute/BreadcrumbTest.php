<?php

namespace SlopeIt\Tests\BreadcrumbBundle\Unit\Attribute;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SlopeIt\BreadcrumbBundle\Attribute\Breadcrumb;

/**
 * @coversDefaultClass \SlopeIt\BreadcrumbBundle\Attribute\Breadcrumb
 */
class BreadcrumbTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * The constructor can also receive an array of breadcrumb items.
     *
     * @covers ::__construct
     */
    public function test_constructor_withMultipleItems()
    {
        $attribute = new Breadcrumb([
            ['label' => 'aLabel', 'route' => 'aRoute', 'params' => ['param' => 'value']],
            ['label' => 'anotherLabel', 'route' => 'anotherRoute']
        ]);

        $this->assertEquals('aLabel', $attribute->items[0]['label']);
        $this->assertEquals('aRoute', $attribute->items[0]['route']);
        $this->assertEquals(['param' => 'value'], $attribute->items[0]['params']);
        $this->assertEquals('anotherLabel', $attribute->items[1]['label']);
        $this->assertEquals('anotherRoute', $attribute->items[1]['route']);
        $this->assertArrayNotHasKey('params', $attribute->items[1]);
    }

    /**
     * The constructor can receive a single breadcrumb item.
     *
     * @covers ::__construct
     */
    public function test_constructor_withSingleItem()
    {
        $attribute = new Breadcrumb([
            'label' => 'aLabel',
            'route' => 'aRoute'
        ]);

        $this->assertEquals('aLabel', $attribute->items[0]['label']);
        $this->assertEquals('aRoute', $attribute->items[0]['route']);
    }
}
