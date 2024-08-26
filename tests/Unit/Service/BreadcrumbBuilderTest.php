<?php

namespace SlopeIt\Tests\BreadcrumbBundle\Unit\Service;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SlopeIt\BreadcrumbBundle\Model\BreadcrumbItem;
use SlopeIt\BreadcrumbBundle\Service\BreadcrumbBuilder;
use SlopeIt\BreadcrumbBundle\Service\BreadcrumbItemFactory;

/**
 * @coversDefaultClass \SlopeIt\BreadcrumbBundle\Service\BreadcrumbBuilder
 */
class BreadcrumbBuilderTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private BreadcrumbBuilder $builder;

    private BreadcrumbItemFactory|MockInterface $itemFactory;

    protected function setUp(): void
    {
        $this->itemFactory = \Mockery::mock(BreadcrumbItemFactory::class);
        $this->builder = new BreadcrumbBuilder($this->itemFactory);
    }

    /**
     * @covers ::__construct
     * @covers ::getItems
     */
    public function test_getItems_emptyBreadcrumb()
    {
        $this->assertEmpty($this->builder->getItems());
    }

    /**
     * @covers ::__construct
     * @covers ::addItem
     * @covers ::getItems
     */
    public function test_getItems_nonEmptyBreadcrumb()
    {
        $item1 = \Mockery::mock(BreadcrumbItem::class);
        $this->itemFactory->expects('create')->with('aLabel', 'aRoute', null, null)->andReturn($item1);
        $item2 = \Mockery::mock(BreadcrumbItem::class);
        $this->itemFactory->expects('create')->with('anotherLabel', 'anotherRoute', [ 'a' => 'b' ], null)
            ->andReturn($item2);

        $this->builder->addItem('aLabel', 'aRoute');
        $this->builder->addItem('anotherLabel', 'anotherRoute', [ 'a' => 'b' ]);

        $this->assertEquals([ $item1, $item2 ], $this->builder->getItems());
    }
}
