<?php

namespace SlopeIt\Tests\BreadcrumbBundle\Unit\Service;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SlopeIt\BreadcrumbBundle\Model\BreadcrumbItem;
use SlopeIt\BreadcrumbBundle\Service\BreadcrumbItemFactory;

/**
 * @coversDefaultClass \SlopeIt\BreadcrumbBundle\Service\BreadcrumbItemFactory
 */
class BreadcrumbItemFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @covers ::create
     */
    public function test_create()
    {
        $factory = new BreadcrumbItemFactory();

        $item = $factory->create('aLabel', 'aRoute');

        $this->assertTrue($item instanceof BreadcrumbItem);
    }
}
