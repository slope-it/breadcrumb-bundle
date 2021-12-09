<?php

namespace SlopeIt\BreadcrumbBundle;

use SlopeIt\BreadcrumbBundle\DependencyInjection\SlopeItBreadcrumbExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SlopeItBreadcrumbBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new SlopeItBreadcrumbExtension();
    }
}
