<?php

namespace SlopeIt\BreadcrumbBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('slope_it_breadcrumb');
        $builder->getRootNode()
            ->children()
                ->scalarNode('template')->defaultValue('@SlopeItBreadcrumb/breadcrumb.html.twig')->end()
            ->end();
        return $builder;
    }
}
