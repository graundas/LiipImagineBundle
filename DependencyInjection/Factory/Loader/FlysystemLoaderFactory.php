<?php

namespace Liip\ImagineBundle\DependencyInjection\Factory\Loader;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class FlysystemLoaderFactory implements LoaderFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(ContainerBuilder $container, $loaderName, array $config)
    {
        $loaderDefinition = new DefinitionDecorator('liip_imagine.binary.loader.prototype.flysystem');
        $loaderDefinition->replaceArgument(2, $config['file_system']);
        $loaderDefinition->addTag('liip_imagine.binary.loader', array(
            'loader' => $loaderName,
        ));
        $loaderId = 'liip_imagine.binary.loader.'.$loaderName;

        $container->setDefinition($loaderId, $loaderDefinition);

        return $loaderId;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'flysystem';
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->scalarNode('file_system')->isRequired()->cannotBeEmpty()->end()
            ->end()
        ;
    }
}
