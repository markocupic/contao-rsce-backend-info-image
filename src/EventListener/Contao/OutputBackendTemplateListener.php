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

use Contao\CoreBundle\Framework\ContaoFramework;
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
    private ContaoFramework $framework;
    private string $rsceBackendInfoImageMarkup;
    private string $addAfterRegexPattern;

    public function __construct(ContaoFramework $framework, string $rsceBackendInfoImageMarkup, string $addAfterRegexPattern)
    {
        $this->framework = $framework;
        $this->rsceBackendInfoImageMarkup = $rsceBackendInfoImageMarkup;
        $this->addAfterRegexPattern = $addAfterRegexPattern;
    }

    public function __invoke(string $buffer, string $template): string
    {
        if ('be_main' !== $template) {
            return $buffer;
        }

        if (null !== ($rsceElement = $this->getRsceElementFromHtmlMarkup($buffer))) {
            $customElementsAdapter = $this->framework->getAdapter(CustomElements::class);

            $config = $customElementsAdapter->getConfigByType($rsceElement);

            if (null !== $config && \is_array($config) && isset($config['backendInfoImage']) && \strlen($config['backendInfoImage'])) {
                $imgSrc = $config['backendInfoImage'];

                $buffer = $this->addPictureToHtmlMarkup($buffer, $rsceElement, $imgSrc, $this->addAfterRegexPattern, $this->rsceBackendInfoImageMarkup);
            }
        }

        return $buffer;
    }

    private function getRsceElementFromHtmlMarkup(string $buffer): ?string
    {
        if (false !== strpos($buffer, 'id="pal_rsce_legend"')) {
            if (preg_match('/<option value="rsce_([a-zA-Z0-9-_]+)" selected>/', $buffer, $matches)) {
                return 'rsce_'.$matches[1];
            }
        }

        return null;
    }

    private function addPictureToHtmlMarkup(string $buffer, string $rsceElement, string $imgSrc, string $addAfterRegexPattern, string $rsceBackendInfoImageMarkup): string
    {
        $search = $addAfterRegexPattern;
        $replacement = "$0$rsceBackendInfoImageMarkup";

        $buffer = preg_replace($search, $replacement, $buffer);
        $buffer = str_replace('###IMAGE_SRC###', $imgSrc, $buffer);

        return str_replace('###IMAGE_ALT###', $rsceElement, $buffer);
    }
}
