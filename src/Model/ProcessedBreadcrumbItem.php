<?php

namespace SlopeIt\BreadcrumbBundle\Model;

/**
 * The final model containing the translated label and the URL (optional) related to a breadcrumb item.
 */
class ProcessedBreadcrumbItem
{
    private ?string $translatedLabel;

    private ?string $url;

    public function __construct(?string $translatedLabel = null, ?string $url = null)
    {
        $this->translatedLabel = $translatedLabel;
        $this->url = $url;
    }

    public function getTranslatedLabel(): ?string
    {
        return $this->translatedLabel;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }
}
