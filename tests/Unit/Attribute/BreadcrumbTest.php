<?php

namespace SlopeIt\Tests\BreadcrumbBundle\Unit\Attribute;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SlopeIt\BreadcrumbBundle\Attribute\Breadcrumb;

#[CoversClass(Breadcrumb::class)]
class BreadcrumbTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    #[Test]
    public function it_can_be_constructed_with_an_array_of_items()
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

    #[Test]
    public function it_can_be_constructed_with_a_single_item()
    {
        $attribute = new Breadcrumb([
            'label' => 'aLabel',
            'route' => 'aRoute'
        ]);

        $this->assertEquals('aLabel', $attribute->items[0]['label']);
        $this->assertEquals('aRoute', $attribute->items[0]['route']);
    }
}
