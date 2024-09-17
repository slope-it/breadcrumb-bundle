<?php

namespace SlopeIt\BreadcrumbBundle\Service;

use SlopeIt\BreadcrumbBundle\Model\BreadcrumbItem;
use Symfony\Contracts\Service\ResetInterface;

/**
 * Provides a minimal interface to create a breadcrumb. This is used by the event listener if attributes are used, but
 * can also be used straight from controllers which want to customize their breadcrumb.
 */
class BreadcrumbBuilder implements ResetInterface
{
    /** @var BreadcrumbItem[] */
    private array $items = [];

    public function addItem(
        ?string $label = null,
        ?string $route = null,
        ?array $routeParams = null,
        string|null|false $translationDomain = null
    ): BreadcrumbBuilder {
        $this->items[] = new BreadcrumbItem($label, $route, $routeParams, $translationDomain);
        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function reset(): void
    {
        // Make sure multiple requests handled in the same loop do not remember breadcrumbs of previous requests.
        $this->items = [];
    }
}
