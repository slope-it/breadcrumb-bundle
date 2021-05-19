<?php

namespace SlopeIt\BreadcrumbBundle;

use SlopeIt\BreadcrumbBundle\DependencyInjection\SlopeItBreadcrumbExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SlopeItBreadcrumbBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new SlopeItBreadcrumbExtension();
    }
}
