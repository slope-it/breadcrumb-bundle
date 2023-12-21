<?php

namespace SlopeIt\BreadcrumbBundle\Model;

/**
 * Acts as a "temporary" model which keeps information for the processor to manipulate. Will ultimately be turned into
 * a ProcessedBreadcrumbItem.
 */
class BreadcrumbItem
{
    private ?string $label;

    private ?string $route;

    private ?array $routeParams;

    /** Null uses the default transation domain, passing "false" avoids translation altogether. */
    private string|null|false $translationDomain;

    public function __construct(
        ?string $label = null,
        ?string $route = null,
        ?array $routeParams = null,
        $translationDomain = null
    ) {
        $this->label = $label;
        $this->route = $route;
        $this->routeParams = $routeParams;
        $this->translationDomain = $translationDomain;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function getRouteParams(): ?array
    {
        return $this->routeParams;
    }

    public function getTranslationDomain(): string|null|false
    {
        return $this->translationDomain;
    }
}
