<?php
declare(strict_types=1);

namespace SlopeIt\Tests\BreadcrumbBundle\Fixtures;

use SlopeIt\BreadcrumbBundle\SlopeItBreadcrumbBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    public function registerBundles(): iterable
    {
        return [
            new MonologBundle(),
            new FrameworkBundle(),
            new SlopeItBreadcrumbBundle(),
        ];
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new class() implements CompilerPassInterface {
            public function process(ContainerBuilder $container): void
            {
                foreach ($container->getDefinitions() as $definition) {
                    $definition->setPublic(true);
                }
            }
        });
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');
    }
}
