<?php

namespace Gbenitez\AttributeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class GbenitezAttributeExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if ($container->hasDefinition('Gbenitez\AttributeBundle\Form\Type\Admin\TargetEntityType')) {
            $definition = $container->getDefinition('Gbenitez\AttributeBundle\Form\Type\Admin\TargetEntityType');
            $definition->replaceArgument('$targetEntityNames', $config['target_entities']);
        }


    }
}
