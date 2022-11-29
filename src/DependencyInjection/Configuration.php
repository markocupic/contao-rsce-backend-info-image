<?php

declare(strict_types=1);

/*
 * This file is part of Contao RSCE Backend Info Image.
 *
 * (c) Marko Cupic 2022 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/contao-rsce-backend-info-image
 */

namespace Markocupic\ContaoRsceBackendInfoImage\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const ROOT_KEY = 'markocupic_contao_rsce_backend_info_image';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::ROOT_KEY);

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('image_markup')
                    ->cannotBeEmpty()
                    ->defaultValue('<div class="long widget rsce-backend-info-image"><div class="rsce-backend-info-image-inner"><img src="###IMAGE_SRC###" title="###IMAGE_TITLE###" alt="###IMAGE_ALT###"></div></div>')
                ->end()
                ->scalarNode('add_after_regex_pattern')
                    ->cannotBeEmpty()
                    ->defaultValue('/<legend onclick="AjaxRequest\.toggleFieldset\(this,\'([a-z]+)_legend\',\'([a-zA-Z0-9-_]+)\'\)">([a-zA-Z0-9 ]+)<\/legend>/')
                ->end()
                ->arrayNode('image_size')
                    ->prototype('scalar')->end()
                    ->defaultValue([600, '', 'proportional'])
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
