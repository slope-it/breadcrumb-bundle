<?php

namespace SlopeIt\Tests\BreadcrumbBundle\DependencyInjection;

use SlopeIt\BreadcrumbBundle\DependencyInjection\Configuration;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * @coversDefaultClass \SlopeIt\BreadcrumbBundle\DependencyInjection\Configuration
 */
class ConfigurationTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * Tests the configuration when overriding the template.
     *
     * @covers ::getConfigTreeBuilder
     */
    public function test_load_overrideTemplate()
    {
        $config = [
            'slope_it_breadcrumb' => [
                'template' => 'aTemplate.html.twig'
            ]
        ];

        $processor = new Processor();
        $processedConfig = $processor->processConfiguration(new Configuration(), $config);

        $this->assertEquals(['template' => 'aTemplate.html.twig'], $processedConfig);
    }
}
