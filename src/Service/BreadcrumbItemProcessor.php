<?php

namespace SlopeIt\BreadcrumbBundle\Service;

use SlopeIt\BreadcrumbBundle\Model\BreadcrumbItem;
use SlopeIt\BreadcrumbBundle\Model\ProcessedBreadcrumbItem;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Transforms BreadcrumbItems in ProcessedBreadcrumbItems by translating or gathering the value for the label and
 * turning the route + parameters into a URL.
 */
class BreadcrumbItemProcessor
{
    /**
     * @var PropertyAccessorInterface
     */
    private $propertyAccessor;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        PropertyAccessorInterface $propertyAccessor,
        TranslatorInterface $translator,
        RouterInterface $router,
        RequestStack $requestStack
    ) {
        $this->propertyAccessor = $propertyAccessor;
        $this->translator = $translator;
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    /**
     * @param array<string, mixed> $variables
     */
    public function process(BreadcrumbItem $item, array $variables): ProcessedBreadcrumbItem
    {
        // Process the label
        if ($item->getLabel() && $item->getLabel()[0] === '$') {
            $processedLabel = $this->parseValue($item->getLabel(), $variables);
        } elseif (!$item->getLabel() || $item->getTranslationDomain() === false) {
            $processedLabel = $item->getLabel();
        } else {
            $processedLabel = $this->translator->trans($item->getLabel(), [], $item->getTranslationDomain());
        }

        // Process the route
        // TODO: cache parameters extracted from current request
        $params = [];
        foreach ($this->requestStack->getCurrentRequest()->attributes as $key => $value) {
            if ($key[0] !== '_') {
                $params[$key] = $value;
            }
        }
        foreach ($item->getRouteParams() ?: [] as $key => $value) {
            if (is_string($value) && $value[0] === '$') {
                $params[$key] = $this->parseValue($value, $variables);
            } else {
                $params[$key] = $value;
            }
        }

        if ($item->getRoute() !== null) {
            $processedUrl = $this->router->generate($item->getRoute(), $params);
        } else {
            $processedUrl = null;
        }

        return new ProcessedBreadcrumbItem($processedLabel, $processedUrl);
    }

    /**
     * Returns the value contained in the variable name (with optional property path) of the given expression.
     *
     * @return mixed
     */
    private function parseValue(string $expression, array $variables)
    {
        $components = explode('.', $expression, 2);
        $variableName = substr($components[0], 1); // Remove the $ prefix;
        $propertyPath = $components[1] ?? null;

        if (!array_key_exists($variableName, $variables)) {
            throw new \RuntimeException('The variables array passed to process the breadcrumb item does not have'
                . ' variable "' . $variableName . '". Make sure you are passing that variable to the template in which'
                . ' this breadcrumb is rendered.');
        }

        if (!$propertyPath) {
            // If this is a "top level" variable, return its value directly.
            return $variables[$variableName];
        }

        return $this->propertyAccessor->getValue($variables[$variableName], $propertyPath);
    }
}
