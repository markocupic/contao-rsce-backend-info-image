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

namespace Markocupic\ContaoRsceBackendInfoImage;

use Markocupic\ContaoRsceBackendInfoImage\DependencyInjection\MarkocupicContaoRsceBackendInfoImageExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MarkocupicContaoRsceBackendInfoImage extends Bundle
{
    public function getContainerExtension(): MarkocupicContaoRsceBackendInfoImageExtension
    {
        return new MarkocupicContaoRsceBackendInfoImageExtension();
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }
}
