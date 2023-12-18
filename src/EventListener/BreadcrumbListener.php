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
        // In case controller is not an array (e.g. a closure or an invokable class), we can't do anything.
        if (!is_array($event->getController())) {
            return;
        }

        list($controller, $action) = $event->getController();

        $class = new \ReflectionClass($controller);
        $method = new \ReflectionMethod($controller, $action);

        $breadcrumbs = [];
        if (($classAttribute = $class->getAttributes(Breadcrumb::class)[0] ?? null)) {
            $breadcrumbs[] = $classAttribute->newInstance();
        }
        if (($methodAttribute = $method->getAttributes(Breadcrumb::class)[0] ?? null)) {
            $breadcrumbs[] = $methodAttribute->newInstance();
        }

        foreach ($breadcrumbs as $breadcrumb) {
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
