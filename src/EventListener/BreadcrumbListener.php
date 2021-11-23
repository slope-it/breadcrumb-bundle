<?php

namespace SlopeIt\BreadcrumbBundle\EventListener;

use SlopeIt\BreadcrumbBundle\Annotation\Breadcrumb;
use SlopeIt\BreadcrumbBundle\Service\BreadcrumbBuilder;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class BreadcrumbListener
{
    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * @var BreadcrumbBuilder
     */
    private $breadcrumbBuilder;

    public function __construct(BreadcrumbBuilder $breadcrumbBuilder, Reader $annotationReader)
    {
        $this->breadcrumbBuilder = $breadcrumbBuilder;
        $this->annotationReader = $annotationReader;
    }

    public function onKernelController(ControllerEvent $event)
    {
        // In case controller is not an array (e.g. a closure or an invokable class), we can't do anything.
        if (!is_array($event->getController())) {
            return;
        }

        list($controller, $action) = $event->getController();

        $class = new \ReflectionClass($controller);
        $method = new \ReflectionMethod($controller, $action);

        $breadcrumbs = [];
        if (($classAnnotation = $this->annotationReader->getClassAnnotation($class, Breadcrumb::class))) {
            $breadcrumbs[] = $classAnnotation;
        }
        if (\PHP_VERSION_ID >= 80000 && ($classAttribute = $class->getAttributes(Breadcrumb::class)[0] ?? null)) {
            $breadcrumbs[] = $classAttribute->newInstance();
        }
        if (($methodAnnotation = $this->annotationReader->getMethodAnnotation($method, Breadcrumb::class))) {
            $breadcrumbs[] = $methodAnnotation;
        }
        if (\PHP_VERSION_ID >= 80000 && ($methodAttribute = $method->getAttributes(Breadcrumb::class)[0] ?? null)) {
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
