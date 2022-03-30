<?php

namespace SlopeIt\BreadcrumbBundle\Twig;

use SlopeIt\BreadcrumbBundle\Service\BreadcrumbBuilder;
use SlopeIt\BreadcrumbBundle\Service\BreadcrumbItemProcessor;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BreadcrumbExtension extends AbstractExtension
{
    /**
     * @var BreadcrumbBuilder
     */
    private $builder;

    /**
     * @var BreadcrumbItemProcessor
     */
    private $itemProcessor;

    /**
     * @var string
     */
    private $template;

    public function __construct(BreadcrumbBuilder $builder, BreadcrumbItemProcessor $itemProcessor, string $template)
    {
        $this->builder = $builder;
        $this->itemProcessor = $itemProcessor;
        $this->template = $template;
    }

    /**
     * {@inheritDoc}
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'slope_it_breadcrumb',
                [$this, 'renderBreadcrumb'],
                [
                    'is_safe' => [ 'html' ],
                    'needs_environment' => true,
                    'needs_context' => true
                ]
            ),
            new TwigFunction(
                'slope_it_is_breadcrumb_empty',
                [$this, 'isBreadcrumbEmpty']
            )
        ];
    }

    public function getName(): string
    {
        return 'slope_it_breadcrumb';
    }

    public function isBreadcrumbEmpty(): bool
    {
        return \count($this->builder->getItems()) === 0;
    }

    public function renderBreadcrumb(Environment $twig, array $context): string
    {
        return $twig->render(
            $this->template,
            [
                'items' => $this->itemProcessor->process($this->builder->getItems(), $context)
            ]
        );
    }
}
