<?php

namespace SlopeIt\BreadcrumbBundle\Annotation;

/**
 * @Annotation
 * @Target({"CLASS","METHOD"})
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Breadcrumb
{
    /**
     * An array of breadcrumb items. Each is an array on its own with the following keys:
     * - label: required, it can be either:
     *   - a translation key - It will be translated right away.
     *   - a string like $variable.property.path - It will be read from the view object contained in the "variable" key,
     *       using a property accessor.
     * - route: optional, if provided must be a valid application route. If the current request already has all the
     *     needed parameters to generate the breadcrumb item url (as it's often the case when URLs are hierarchical)
     *     you don't need to provide the "params" array.
     * - params: optional, if provided must be an array of key-value parameters needed to generate the route. Parameter
     *     values can be provided either statically or using the same $variable.property.path syntax used by "label".
     *     See note above regarding the "route" key to know when you need to provide route params explicitly.
     *
     * @var array
     */
    public $items;

    /**
     * @param array $items An array of items or a single item.
     */
    public function __construct(array $items)
    {
        // 'value' is present in case this class is used as annotation.
        if (array_key_exists('value', $items)) {
            $items = $items['value'];
        }

        // $items can be either a single item or an array of items, for convenience. Normalize it to an array of items.
        if (array_key_exists('label', $items)) {
            $items = [$items];
        }

        $this->items = $items;
    }
}
