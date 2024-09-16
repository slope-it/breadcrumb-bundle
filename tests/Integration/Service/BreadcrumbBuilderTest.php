<?php
declare(strict_types=1);

namespace Integration\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use SlopeIt\BreadcrumbBundle\Model\BreadcrumbItem;
use SlopeIt\BreadcrumbBundle\Service\BreadcrumbBuilder;
use SlopeIt\Tests\BreadcrumbBundle\Fixtures\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

#[CoversClass(BreadcrumbBuilder::class)]
class BreadcrumbBuilderTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    #[Test]
    public function it_forgets_the_breadcrumb_when_container_is_reset()
    {
        // Action
        /** @var BreadcrumbBuilder $SUT */
        $SUT = self::getContainer()->get(BreadcrumbBuilder::class);
        $SUT->addItem('anItem');

        // Verification: breadcrumb item is present
        $this->assertEquals([new BreadcrumbItem('anItem')], $SUT->getItems());

        // Action 2: reset the actual container (NOT the test one, as it ignores the `reset` call!)
        self::$kernel->getContainer()->reset();

        // Verification 2: breadcrumb item is not present anymore
        $this->assertEquals([], $SUT->getItems());
    }
}
