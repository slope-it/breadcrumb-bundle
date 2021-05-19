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

        $annotations = [];
        if (($classAnnotation = $this->annotationReader->getClassAnnotation($class, Breadcrumb::class))) {
            $annotations[] = $classAnnotation;
        }
        if ($methodAnnotation = $this->annotationReader->getMethodAnnotation($method, Breadcrumb::class)) {
            $annotations[] = $methodAnnotation;
        }

        foreach ($annotations as $annotation) {
            foreach ($annotation->items as $item) {
                $this->breadcrumbBuilder->addItem(
                    $item['label'],
                    isset($item['route']) ? $item['route'] : null,
                    isset($item['params']) ? $item['params'] : null,
                    isset($item['translationDomain']) ? $item['translationDomain'] : null
                );
            }
        }
    }
}
