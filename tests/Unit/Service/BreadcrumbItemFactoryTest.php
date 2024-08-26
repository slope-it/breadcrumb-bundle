<?php

namespace SlopeIt\Tests\BreadcrumbBundle\Unit\Service;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SlopeIt\BreadcrumbBundle\Service\BreadcrumbItemFactory;

#[CoversClass(BreadcrumbItemFactory::class)]
class BreadcrumbItemFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function test_create()
    {
        $factory = new BreadcrumbItemFactory();

        $item = $factory->create('aLabel', 'aRoute');

        $this->assertSame('aLabel', $item->getLabel());
        $this->assertSame('aRoute', $item->getRoute());
    }
}
