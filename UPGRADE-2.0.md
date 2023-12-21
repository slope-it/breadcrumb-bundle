# UPGRADE FROM 1.x to 2.0

- Removed annotations in favor of attributes. Migration should be straightforward, here's a quick example:

Before:
```php
use SlopeIt\BreadcrumbBundle\Annotation\Breadcrumb;

/**
 * @Breadcrumb({
 *   { "label" = "home", "route" = "home_route" },
 * })
 */
class CoolController extends Controller
```

After:
```php
use SlopeIt\BreadcrumbBundle\Attribute\Breadcrumb;

#[Breadcrumb(['label' => 'home', 'route' => 'home_route'])]
class CoolController extends Controller
```
