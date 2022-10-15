<?php

namespace Emrdev\SfBugBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;


class SfBugExtension extends Extension implements PrependExtensionInterface
{

    public function prepend(ContainerBuilder $container)
    {
        $thirdPartyBundlesViewFileLocator = (new FileLocator(__DIR__ . '/../Resources/views/bundles'));
        $container->loadFromExtension('twig', [
            'paths' => [
                $thirdPartyBundlesViewFileLocator->locate('WebProfilerBundle/Resources/views') => 'WebProfiler',
            ],
        ]);
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('sfbug.php');
    }
}
