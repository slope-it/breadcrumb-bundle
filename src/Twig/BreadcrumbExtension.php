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

    /**
     * @param BreadcrumbBuilder       $builder
     * @param BreadcrumbItemProcessor $itemProcessor
     * @param string                  $template
     */
    public function __construct(BreadcrumbBuilder $builder, BreadcrumbItemProcessor $itemProcessor, $template)
    {
        $this->builder = $builder;
        $this->itemProcessor = $itemProcessor;
        $this->template = $template;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'slope_it_breadcrumb',
                [ $this, 'renderBreadcrumb' ],
                [
                    'is_safe' => [ 'html' ],
                    'needs_environment' => true,
                    'needs_context' => true
                ]
            )
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'slope_it_breadcrumb';
    }

    /**
     * Returns the rendered breadcrumb.
     *
     * @return string
     */
    public function renderBreadcrumb(Environment $twig, array $context)
    {
        $breadcrumb = [];
        foreach ($this->builder->getItems() as $item) {
            $breadcrumb[] = $this->itemProcessor->process($item, $context);
        }

        return $twig->render($this->template, [ 'items' => $breadcrumb ]);
    }
}
