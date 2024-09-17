<?php

namespace SlopeIt\Tests\BreadcrumbBundle\Unit\DependencyInjection;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SlopeIt\BreadcrumbBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

#[CoversClass(Configuration::class)]
class ConfigurationTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    #[Test]
    public function it_allows_to_override_the_default_template()
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
