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
use Contao\CoreBundle\Image\ImageFactory;
use Contao\CoreBundle\ServiceAnnotation\Hook;
use Contao\File;
use MadeYourDay\RockSolidCustomElements\CustomElements;
use Symfony\Component\Filesystem\Path;

/**
 * This will add an image to each rocksolid custom element in the edit view of the Contao backend.
 * The image can be defined in the "backendInfoImage" key of the rsce_my_element_config.php.
 *
 * @Hook("outputBackendTemplate")
 */
class OutputBackendTemplateListener
{
    private ContaoFramework $framework;
    private ImageFactory $contaoImageFactory;
    private string $projectDir;
    private string $rsceBackendInfoImageMarkup;
    private string $addAfterRegexPattern;
    private array $imageSize;

    public function __construct(ContaoFramework $framework, ImageFactory $contaoImageFactory, string $projectDir, string $rsceBackendInfoImageMarkup, string $addAfterRegexPattern, array $imageSize)
    {
        $this->framework = $framework;
        $this->contaoImageFactory = $contaoImageFactory;
        $this->projectDir = $projectDir;
        $this->rsceBackendInfoImageMarkup = $rsceBackendInfoImageMarkup;
        $this->addAfterRegexPattern = $addAfterRegexPattern;
        $this->imageSize = $imageSize;
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
                $this->generateImage($imgSrc);
                $buffer = $this->addPictureToHtmlMarkup($buffer, $rsceElement, $this->generateImage($imgSrc), $this->addAfterRegexPattern, $this->rsceBackendInfoImageMarkup);
            }
        }

        return $buffer;
    }

    private function getRsceElementFromHtmlMarkup(string $buffer): string|null
    {
        if (false !== strpos($buffer, '<input type="hidden" name="rsce_data" value="">')) {
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

        $buffer = preg_replace($search, $replacement, $buffer, 1);
        $buffer = str_replace('###IMAGE_SRC###', $imgSrc, $buffer);
        $buffer = str_replace('###IMAGE_TITLE###', $rsceElement, $buffer);

        return str_replace('###IMAGE_ALT###', $rsceElement, $buffer);
    }

    private function generateImage(string $filesystemPath): string
    {
        $path = Path::isAbsolute($filesystemPath)
            ? Path::canonicalize($filesystemPath)
            : Path::join($this->projectDir, $filesystemPath);

        if (is_file($path)) {
            $file = new File($path);

            if ($file && $file->isImage) {
                $image = $this->contaoImageFactory->create(
                    $path,
                    $this->imageSize,
                );

                $path = $image->getUrl($this->projectDir);
            }
        }

        return $path;
    }
}
