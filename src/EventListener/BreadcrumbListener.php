<?php

namespace SlopeIt\BreadcrumbBundle\EventListener;

use SlopeIt\BreadcrumbBundle\Attribute\Breadcrumb;
use SlopeIt\BreadcrumbBundle\Service\BreadcrumbBuilder;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class BreadcrumbListener
{
    private BreadcrumbBuilder $breadcrumbBuilder;

    public function __construct(BreadcrumbBuilder $breadcrumbBuilder)
    {
        $this->breadcrumbBuilder = $breadcrumbBuilder;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        foreach ($event->getAttributes(Breadcrumb::class) as $breadcrumb) {
            foreach ($breadcrumb->items as $item) {
                $this->breadcrumbBuilder->addItem(
                    $item['label'],
                    $item['route'] ?? null,
                    $item['params'] ?? null,
                    $item['translationDomain'] ?? null
                );
            }
        }
    }
}
