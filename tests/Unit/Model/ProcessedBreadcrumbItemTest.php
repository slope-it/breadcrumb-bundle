<?php

namespace SlopeIt\Tests\BreadcrumbBundle\Unit\Model;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SlopeIt\BreadcrumbBundle\Model\ProcessedBreadcrumbItem;

/**
 * @coversDefaultClass \SlopeIt\BreadcrumbBundle\Model\ProcessedBreadcrumbItem
 */
class ProcessedBreadcrumbItemTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @covers ::__construct
     * @covers ::getTranslatedLabel
     * @covers ::getUrl
     */
    public function test_constructor()
    {
        $item = new ProcessedBreadcrumbItem('translated label', '/url');

        $this->assertEquals('translated label', $item->getTranslatedLabel());
        $this->assertEquals('/url', $item->getUrl());
    }
}
