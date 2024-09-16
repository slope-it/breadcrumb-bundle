<?php

namespace SlopeIt\Tests\BreadcrumbBundle\Unit\EventListener;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SlopeIt\BreadcrumbBundle\EventListener\BreadcrumbListener;
use SlopeIt\BreadcrumbBundle\Service\BreadcrumbBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

#[CoversClass(BreadcrumbListener::class)]
class BreadcrumbListenerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    #[Test]
    public function it_does_not_try_to_parse_attributes_when_controller_is_not_an_array()
    {
        $breadcrumbBuilder = \Mockery::mock(BreadcrumbBuilder::class);

        $SUT = new BreadcrumbListener($breadcrumbBuilder);

        $event = new ControllerEvent(
            \Mockery::spy(HttpKernelInterface::class),
            function () {}, // Controller is a callable
            \Mockery::mock(Request::class),
            HttpKernelInterface::MAIN_REQUEST
        );

        $breadcrumbBuilder->allows('addItem')->never();

        $SUT->onKernelController($event);
    }
}
