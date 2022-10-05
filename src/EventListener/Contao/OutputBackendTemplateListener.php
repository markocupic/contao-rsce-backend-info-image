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

namespace Markocupic\ContaoRsceBackendInfoImage\EventListener\Contao;

use Contao\CoreBundle\ServiceAnnotation\Hook;
use MadeYourDay\RockSolidCustomElements\CustomElements;

/**
 * This will add an image to each rocksolid custom element in the edit view of the Contao backend.
 * The image can be defined in the "backendInfoImage" key of the rsce_my_element_config.php.
 *
 * @Hook("outputBackendTemplate")
 */
class OutputBackendTemplateListener
{
    private string $rsceBackendInfoImageMarkup;
    private string $addAfterRegexPattern;

    public function __construct(string $rsceBackendInfoImageMarkup, string $addAfterRegexPattern)
    {
        $this->rsceBackendInfoImageMarkup = $rsceBackendInfoImageMarkup;
        $this->addAfterRegexPattern = $addAfterRegexPattern;
    }

    public function __invoke(string $buffer, string $template): string
    {
        if ('be_main' !== $template) {
            return $buffer;
        }

        if (false !== strpos($buffer, 'id="pal_rsce_legend"')) {
            if (preg_match('/<option value="rsce_([a-zA-Z0-9-_]+)" selected>/', $buffer, $matches)) {
                $rsceElement = 'rsce_'.$matches[1];
                $config = CustomElements::getConfigByType($rsceElement);

                if (null !== $config && \is_array($config) && isset($config['backendInfoImage']) && \strlen($config['backendInfoImage'])) {
                    $search = $this->addAfterRegexPattern;
                    $replacement = '$0'.$this->rsceBackendInfoImageMarkup;

                    $buffer = preg_replace($search, $replacement, $buffer);
                    $buffer = str_replace('###IMAGE_SRC###', $config['backendInfoImage'], $buffer);
                    $buffer = str_replace('###IMAGE_ALT###', $rsceElement, $buffer);
                }
            }
        }

        return $buffer;
    }
}
