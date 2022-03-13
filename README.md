# SlopeItBreadcrumbBundle

[![Latest Stable Version](https://poser.pugx.org/slope-it/breadcrumb-bundle/v/stable)](https://packagist.org/packages/slope-it/breadcrumb-bundle)
[![Total Downloads](https://poser.pugx.org/slope-it/breadcrumb-bundle/downloads)](https://packagist.org/packages/slope-it/breadcrumb-bundle)
[![License](https://poser.pugx.org/slope-it/breadcrumb-bundle/license)](https://packagist.org/packages/slope-it/breadcrumb-bundle)

This bundle provides a way to create "dynamic" breadcrumbs in your Symfony applications.

## Installation

Composer is the only supported installation method. Run the following to install the latest version from Packagist:

```bash
composer require slope-it/breadcrumb-bundle
```

Or, if you prefer, you can require any version in your `composer.json`:

```json
{
    "require": {
        "slope-it/breadcrumb-bundle": "*"
    }
}
```

## Usage

### 1. Load bundle

Once installed, load the bundle in your Kernel class:

```php
// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new SlopeIt\BreadcrumbBundle\SlopeItBreadcrumbBundle(),
        );
        // ...
    }
    // ...
}
```

### 2. Define breadcrumbs

There are three ways to create a breadcrumb: via code (1), via attributes (2) (PHP 8.0+) or via annotations (3).

*Via code*: you can inject the breadcrumb builder in your controller and add breadcrumb items:

```php
<?php

use SlopeIt\BreadcrumbBundle\Service\BreadcrumbBuilder;

class CoolController extends Controller
{
    public function coolStuffAction(BreadcrumbBuilder $builder)
    {
        $builder->addItem('home', 'home_route');
        $builder->addItem('$entity.property', 'entity_route');
        $builder->addItem('cool_stuff');
    
        // ...
    }
}
```

*Via attributes*: just use the `#[Breadcrumb]` attribute at the class and/or method level. They will be merged (class comes first).

*NOTE:* The attribute can take either a single item (in the example it's done at the class level) or multiple items (in the example, at the method level).

```php
<?php

use SlopeIt\BreadcrumbBundle\Annotation\Breadcrumb;

#[Breadcrumb([
  'label' => 'home',
  'route' => 'home_route',
  'params' => ['p' => 'val'],
  'translationDomain' => 'domain',
])]
class CoolController extends Controller
{
    #[Breadcrumb([
        ['label' => '$entity.property', 'route' => 'entity_route'], 
        ['label' => 'cool_stuff'], 
    ])]
    public function coolStuffAction()
    {
        // ...
    }
}
```

*Via annotations*: just use the `@Breadcrumb` annotation at the class and/or method level. They will be merged (class comes first).

*NOTE:* The annotation can take either a single item (in the example it's done at the class level) or multiple items (in the example, at the method level).

```php
<?php

use SlopeIt\BreadcrumbBundle\Annotation\Breadcrumb;

/**
 * @Breadcrumb({"label" = "home", "route" = "home_route", "params" = {"p" = "val"}, "translationDomain" = "domain" })
 */
class CoolController extends Controller
{
    /**
     * @Breadcrumb({
     *   { "label" = "$entity.property", "route" = "entity_route" },
     *   { "label" = "cool_stuff" }
     * })
     */
    public function coolStuffAction()
    {
        // ...
    }
```

### 3. Render breadcrumb

The last step is to use the following Twig function wherever you want the breadcrumb printed in your template:

```
{{ slope_it_breadcrumb() }}
```

Regardless of the way you used to create the breadcrumb, the result will be something like:

```
Home > Value of entity property > Cool stuff
```

In which the first two items are anchors and the last one is text only.

### How the breadcrumb is generated

Under the hood, this is the business logic involved, for each item, in the breadcrumb generation:
* `label` will be the printed text. It can be either:
  * A "static" string (the translator will attempt to translate it by using it as a translation key)
  * A special string, prepended with `$`. In this case, the breadcrumb label will be extracted from the variable passed to the template. Property paths can be used, e.g.: `$variable.property.path`.
  * You can even build a complex label by mixing static string and multiple variables: `Gallery "$gallery.title" - $gallery.year`.
* `route` will be used to generate the url for the item anchor (if provided). If not provided, the item will not be clickable.
* `params` will be used to generate the url related to the provided route. It's an associative array where each value can be either:
  * A "static" string
  * A "special" string using the format `$variable.property.path`. The treatment is exactly the same as in "label". This is useful to dynamically retrieve url params (e.g. entity ID) starting from view variables.
* `translationDomain` will be used to translate the key provided in the `label` attribute. If `null`, the default translation domain will be used. Provide `false` if you put a non-translatable string in `label` and you don't want to use it as a translation key.

*NOTE:* **you don't need to pass all the route parameters that are needed by route, as long as these route parameters are already present in the URL for the current request**. In other words, if your breadcrumb hierarchical structure somehow "matches" your URL structure.

Example: suppose you have the following routes, with parameters and resulting URLs:

```
parent_list   |                                       | /parents
parent_view   | { parent_id: 12345 }                  | /parents/12345
children_list | { parent_id: 12345 }                  | /parents/12345/children
child_view    | { parent_id: 12345, child_id: 67890 } | /parents/12345/children/67890
child_edit    | { parent_id: 12345, child_id: 67890 } | /parents/12345/children/67890/edit
```

If you are in the action for route `children_edit` and you want to generate a breadcrumb including all the above steps, you will be able to use the following annotation:

```php
/**
 * @Breadcrumb({
 *   { "label" = "parents", "route" = "parent_list" },
 *   { "label" = "$parent.name", "route" = "parent_view" },
 *   { "label" = "children", "route" = "children_list" },
 *   { "label" = "$child.name", "route" = "child_view" },
 *   { "label" = "edit" }
 * })
 */
public function childrenEditAction($parentID, $childrenID)
{
    // ...
}
```

Note how you don't have to provide the route parameters (since the current request already has them all). It would work the same if you build it via code instead.

## Override breadcrumb template

The bundle default template for rendering breadcrumb can be overridden by adding the following lines to the `config.yml` of your application:

```yml
slope_it_breadcrumb:
    template: YourBundle::breadcrumb.html.twig
```

If you intend to do this, it's recommended to copy `Resources/views/breadcrumb.html.twig` to your bundle and customize it.
However, in your template you'll just have to iterate over the `items` variable to render your custom breadcrumb.

## How to contribute

* Did you find and fix any bugs in the existing code?
* Do you want to contribute a new feature?
* Do you think documentation can be improved?

Under any of these circumstances, please fork this repo and create a pull request. We are more than happy to accept contributions!

## Maintainer

[@andreasprega](https://twitter.com/andreasprega)
