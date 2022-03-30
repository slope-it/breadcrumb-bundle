<?php

namespace SlopeIt\Tests\BreadcrumbBundle\Unit\Service;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use SlopeIt\BreadcrumbBundle\Model\BreadcrumbItem;
use SlopeIt\BreadcrumbBundle\Service\BreadcrumbItemProcessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @coversDefaultClass \SlopeIt\BreadcrumbBundle\Service\BreadcrumbItemProcessor
 */
class BreadcrumbItemProcessorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var BreadcrumbItemProcessor
     */
    private $SUT;

    /**
     * @var PropertyAccessorInterface|\Mockery\MockInterface
     */
    private $propertyAccessor;

    /**
     * @var RequestStack|\Mockery\MockInterface
     */
    private $requestStack;

    /**
     * @var UrlGeneratorInterface|\Mockery\MockInterface
     */
    private $urlGenerator;

    /**
     * @var TranslatorInterface|\Mockery\MockInterface
     */
    private $translator;

    protected function setUp(): void
    {
        $this->propertyAccessor = \Mockery::mock(PropertyAccessorInterface::class);
        $this->translator = \Mockery::mock(TranslatorInterface::class);
        $this->urlGenerator = \Mockery::mock(
            UrlGeneratorInterface::class,
            [
                'getContext' => \Mockery::spy(RequestContext::class, ['getParameters' => []])
            ]
        );
        $this->requestStack = \Mockery::mock(RequestStack::class);

        $currentRequest = \Mockery::mock(Request::class);
        $currentRequest->attributes = [];
        $this->requestStack->allows('getCurrentRequest')->andReturn($currentRequest);

        $this->SUT = new BreadcrumbItemProcessor(
            $this->propertyAccessor,
            $this->translator,
            $this->urlGenerator,
            $this->requestStack
        );
    }

    /**
     * @covers ::process
     */
    public function test_process_item_with_label_as_simple_variable()
    {
        $item = new BreadcrumbItem('$variableName');

        $processedItems = $this->SUT->process([$item], ['variableName' => 'variableValue']);

        $this->assertSame('variableValue', $processedItems[0]->getTranslatedLabel());
    }

    /**
     * @covers ::process
     */
    public function test_process_item_with_label_as_variable_with_property_path()
    {
        $item = new BreadcrumbItem('$variableName.property.nestedProperty');

        $object = (object) ['property' => (object) ['nestedProperty' => 'propertyValue']];

        $this->propertyAccessor->expects('getValue')->with($object, 'property.nestedProperty')
            ->andReturn('propertyValue');

        $processedItems = $this->SUT->process([$item], ['variableName' => $object]);

        $this->assertSame('propertyValue', $processedItems[0]->getTranslatedLabel());
    }

    /**
     * @covers ::process
     */
    public function test_process_item_with_label_not_to_be_translated()
    {
        $item = new BreadcrumbItem('Already translated label', null, null, false);

        $processedItems = $this->SUT->process([$item], []);

        $this->assertSame('Already translated label', $processedItems[0]->getTranslatedLabel());
    }

    /**
     * @covers ::process
     */
    public function test_process_item_with_label_to_be_translated_with_default_translation_domain()
    {
        $item = new BreadcrumbItem('translatable_key');

        $this->translator->expects('trans')->with('translatable_key', [], null)
            ->andReturn('Translated label');

        $processedItems = $this->SUT->process([$item], []);

        $this->assertSame('Translated label', $processedItems[0]->getTranslatedLabel());
    }

    /**
     * @covers ::process
     */
    public function test_process_item_with_label_to_be_translated_with_specific_translation_domain()
    {
        $item = new BreadcrumbItem('translatable_key', null, null, 'custom_domain');

        $this->translator->expects('trans')->with('translatable_key', [], 'custom_domain')
            ->andReturn('Translated label');

        $processedItems = $this->SUT->process([$item], []);

        $this->assertSame('Translated label', $processedItems[0]->getTranslatedLabel());
    }

    /**
     * @covers ::process
     */
    public function test_process_item_with_null_label()
    {
        $item = new BreadcrumbItem();

        $processedItems = $this->SUT->process([$item], []);

        $this->assertNull($processedItems[0]->getTranslatedLabel());
    }
}
