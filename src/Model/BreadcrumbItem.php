<?php

namespace SlopeIt\BreadcrumbBundle\Model;

/**
 * Acts as a "temporary" model which keeps information for the processor to manipulate. Will ultimately be turned into
 * a ProcessedBreadcrumbItem.
 */
class BreadcrumbItem
{
    public readonly ?string $label;

    public readonly ?string $route;

    public readonly ?array $routeParams;

    /** Null uses the default transation domain, passing "false" avoids translation altogether. */
    public readonly string|null|false $translationDomain;

    public function __construct(
        ?string $label = null,
        ?string $route = null,
        ?array $routeParams = null,
        string|null|false $translationDomain = null
    ) {
        $this->label = $label;
        $this->route = $route;
        $this->routeParams = $routeParams;
        $this->translationDomain = $translationDomain;
    }
}
