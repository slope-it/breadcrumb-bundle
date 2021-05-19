<?php

namespace SlopeIt\BreadcrumbBundle\Model;

/**
 * Acts as a "temporary" model which keeps information for the processor to manipulate. Will ultimately be turned into
 * a ProcessedBreadcrumbItem.
 */
class BreadcrumbItem
{
    /**
     * @var string|null
     */
    private $label;

    /**
     * @var string|null
     */
    private $route;

    /**
     * @var array|null
     */
    private $routeParams;

    /**
     * @var string|null|false - Null uses the default transation domain, passing "false" avoids translation altogether.
     */
    private $translationDomain;

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

    /**
     * @return false|string|null
     */
    public function getTranslationDomain()
    {
        return $this->translationDomain;
    }
}
