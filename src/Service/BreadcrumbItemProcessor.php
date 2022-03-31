<?php

namespace SlopeIt\BreadcrumbBundle\Service;

use SlopeIt\BreadcrumbBundle\Model\BreadcrumbItem;
use SlopeIt\BreadcrumbBundle\Model\ProcessedBreadcrumbItem;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        PropertyAccessorInterface $propertyAccessor,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        RequestStack $requestStack
    ) {
        $this->propertyAccessor = $propertyAccessor;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
        $this->requestStack = $requestStack;
    }

    /**
     * @param BreadcrumbItem[] $items
     * @param array<string, mixed> $variables
     * @return ProcessedBreadcrumbItem[]
     */
    public function process(array $items, array $variables): array
    {
        $requestContext = $this->urlGenerator->getContext();

        // Save original parameters of the request context, so they can be re-applied after url generation is done.
        $originalRequestContextParameters = $requestContext->getParameters();

        // Use the request context to pre-set parameters that are already present as attributes of the current request.
        // This allows for URL generation using parameters already present in the request path without having to
        // re-specify them all the time. These parameters won't be added unnecessarily if they are not needed for the
        // route being generated.
        foreach ($this->requestStack->getCurrentRequest()->attributes as $key => $value) {
            if ($key[0] !== '_') {
                $requestContext->setParameter($key, $value);
            }
        }

        // Process items with our own "modified" request context
        $processedItems = array_map(
            function (BreadcrumbItem $item) use ($variables) {
                return $this->processItem($item, $variables);
            },
            $items
        );

        // Restore parameters originally present in the context so that this method doesn't have any side effects.
        $this->urlGenerator->getContext()->setParameters($originalRequestContextParameters);

        return $processedItems;
    }

    /**
     * @param array<string, mixed> $variables
     */
    private function processItem(BreadcrumbItem $item, array $variables): ProcessedBreadcrumbItem
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
        if ($item->getRoute() !== null) {
            $params = [];
            foreach ($item->getRouteParams() ?: [] as $key => $value) {
                if (is_string($value) && $value[0] === '$') {
                    $params[$key] = $this->parseValue($value, $variables);
                } else {
                    $params[$key] = $value;
                }
            }

            $processedUrl = $this->urlGenerator->generate($item->getRoute(), $params);
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
