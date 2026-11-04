# UPGRADE FROM 2.x to 3.0

- Removed feature to dynamically get label content from view labels.

Before:
```php
use SlopeIt\BreadcrumbBundle\Attribute\Breadcrumb;

class FooBarController extends Controller
{
    #[Breadcrumb(['label' => 'some_label'])]
    #[Breadcrumb(['label' => '$object.property', 'route' => 'action'])]
    public function action(): Response
    {
        $object = new stdClass();
        $object->property = 'foobar';
        ...
        return $this->render(
            'view',
            [
                'object' => $object,
            ]
        );
    }
}
```

After:
```php
use SlopeIt\BreadcrumbBundle\Attribute\Breadcrumb;
use SlopeIt\BreadcrumbBundle\Service\BreadcrumbBuilder;

class FooBarController extends Controller
{
    #[Breadcrumb(['label' => 'some_label'])]
    public function action(BreadcrumbBuilder $builder): Response
    {
        $object = new stdClass();
        $object->property = 'foobar';
        ...
        $builder->addItem($object->property, route: 'action');

        return $this->render(
            'view',
            [
                'object' => $object,
            ]
        );
    }
}
```
