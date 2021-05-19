<?php

namespace SlopeIt\Tests\BreadcrumbBundle\Model;

use SlopeIt\BreadcrumbBundle\Model\ProcessedBreadcrumbItem;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

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
