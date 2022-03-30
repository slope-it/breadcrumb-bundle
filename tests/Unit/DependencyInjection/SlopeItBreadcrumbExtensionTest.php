<?php
declare(strict_types=1);

namespace SlopeIt\Tests\BreadcrumbBundle\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use SlopeIt\BreadcrumbBundle\DependencyInjection\SlopeItBreadcrumbExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SlopeItBreadcrumbExtensionTest extends TestCase
{
    /**
     * @test
     */
    public function its_template_can_be_overridden_via_configuration()
    {
        $container = new ContainerBuilder();

        $extension = new SlopeItBreadcrumbExtension();
        $config = [
            'template' => 'some-template.html.twig',
        ];

        $extension->load([$config], $container);

        $this->assertSame('some-template.html.twig', $container->getParameter('slope_it_breadcrumb.template'));
    }
}
