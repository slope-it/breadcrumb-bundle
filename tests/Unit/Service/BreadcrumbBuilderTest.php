<?php

namespace SlopeIt\Tests\BreadcrumbBundle\Unit\Service;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SlopeIt\BreadcrumbBundle\Model\BreadcrumbItem;
use SlopeIt\BreadcrumbBundle\Service\BreadcrumbBuilder;

#[CoversClass(BreadcrumbBuilder::class)]
class BreadcrumbBuilderTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private BreadcrumbBuilder $SUT;

    protected function setUp(): void
    {
        $this->SUT = new BreadcrumbBuilder();
    }

    #[Test]
    public function it_allows_to_add_items()
    {
        $this->SUT->addItem('aLabel', 'aRoute');
        $this->SUT->addItem('anotherLabel', 'anotherRoute', ['a' => 'b']);

        $this->assertEquals(
            [new BreadcrumbItem('aLabel', 'aRoute'), new BreadcrumbItem('anotherLabel', 'anotherRoute', ['a' => 'b'])],
            $this->SUT->getItems()
        );
    }
}
