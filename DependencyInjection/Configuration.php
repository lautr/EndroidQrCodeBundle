<?php

/*
 * (c) Jeroen van den Enden <info@endroid.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Endroid\Bundle\QrCodeBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Yaml\Yaml;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $config = Yaml::parse(file_get_contents(__DIR__.'/../Resources/config/config.yml'));

        $treeBuilder = new TreeBuilder();

        $treeBuilder
            ->root('endroid_qr_code')
                ->children()
                    ->integerNode('size')
                        ->cannotBeEmpty()
                        ->min(0)
                        ->defaultValue($config['size'])
                    ->end()
                    ->integerNode('padding')
                        ->cannotBeEmpty()
                        ->min(0)
                        ->defaultValue($config['padding'])
                    ->end()
                    ->scalarNode('extension')
                        ->cannotBeEmpty()
                        ->defaultValue($config['extension'])
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
