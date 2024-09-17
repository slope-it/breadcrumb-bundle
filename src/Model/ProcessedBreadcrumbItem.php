<?php

namespace SlopeIt\BreadcrumbBundle\Model;

/**
 * The final model containing the translated label and the URL (optional) related to a breadcrumb item.
 */
class ProcessedBreadcrumbItem
{
    public readonly ?string $translatedLabel;

    public readonly ?string $url;

    public function __construct(?string $translatedLabel = null, ?string $url = null)
    {
        $this->translatedLabel = $translatedLabel;
        $this->url = $url;
    }
}
