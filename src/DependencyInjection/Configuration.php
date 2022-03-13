<?php

namespace SlopeIt\BreadcrumbBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('slope_it_breadcrumb');
        $builder->getRootNode()
            ->children()
                ->booleanNode('extract_current_route_parameters')->defaultTrue()->end()
                ->scalarNode('template')->defaultValue('@SlopeItBreadcrumb/breadcrumb.html.twig')->end()
            ->end();

        return $builder;
    }
}
