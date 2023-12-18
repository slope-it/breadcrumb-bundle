<?php

namespace SlopeIt\BreadcrumbBundle\Service;

use SlopeIt\BreadcrumbBundle\Model\BreadcrumbItem;

/**
 * Provides a minimal interface to create a breadcrumb. This is used by the event listener if attributes are used, but
 * can also be used straight from controllers which want to customize their breadcrumb.
 */
class BreadcrumbBuilder
{
    private BreadcrumbItemFactory $itemFactory;

    /** @var BreadcrumbItem[] */
    private array $items = [];

    public function __construct(BreadcrumbItemFactory $itemFactory)
    {
        $this->itemFactory = $itemFactory;
    }

    public function addItem(
        ?string $label = null,
        ?string $route = null,
        ?array $routeParams = null,
        $translationDomain = null
    ): BreadcrumbBuilder {
        $this->items[] = $this->itemFactory->create($label, $route, $routeParams, $translationDomain);
        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
