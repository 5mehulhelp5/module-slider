<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model;

use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Resizer
{
    private ImageManager $imageManager;

    public function __construct(
        private DirectoryList $directoryList,
        private Filesystem $filesystem,
        private File $file,
        private StoreManagerInterface $storeManager,
        private LoggerInterface $logger,
        private string $basePath = 'mygentoslider/banner',
    ) {
        $driver = extension_loaded('imagick') ? new ImagickDriver() : new GdDriver();
        $this->imageManager = new ImageManager($driver);
    }

    public function resizeAndConvert(string $imagePath, ?string $ext, ?int $width = null, ?int $height = null): ?string
    {
        $write = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $mediaPath = $this->directoryList->getPath(DirectoryList::MEDIA);

        $imageName = ltrim(str_replace($this->basePath, '', $imagePath), '/');
        $inputPath = $mediaPath . '/' . $this->basePath . '/' . $imageName;

        if (!$write->isExist($inputPath)) {
            throw new LocalizedException(__('Source image not found: %1', $imagePath));
        }

        if (null === $width && null === $height) {
            return null;
        }

        $imageInfo = $this->file->getPathInfo($imageName);
        $folder = $imageInfo['dirname'] ?? '.';

        $cachePath = '/cache/' . ($width ?? '') . 'x' . ($height ?? '');
        $outputDir = $mediaPath . '/' . $this->basePath . $cachePath . ($folder === '.' ? '' : '/' . $folder);

        $write->create($outputDir);

        $fileNoExt = $imageInfo['filename'];
        if (!$fileNoExt) {
            return null;
        }

        $outputPath = $outputDir . '/' . $fileNoExt . '.' . ($ext ?? $imageInfo['extension']);
        if ($write->isExist($outputPath)) {
            return $this->fileToUrl($outputPath);
        }

        return $this->scale($inputPath, $outputPath, $width, $height);
    }

    private function fileToUrl(string $file): string
    {
        $mediaBaseUrl = rtrim($this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA), '/');
        $mediaPath = $this->directoryList->getPath(DirectoryList::MEDIA);

        return str_replace($mediaPath, $mediaBaseUrl, $file);
    }

    private function scale(string $inputPath, string $outputPath, ?int $width = null, ?int $height = null): ?string
    {
        try {
            $image = $this->imageManager->read($inputPath);
            $origW = $image->width();
            $origH = $image->height();

            // Check whether upscaling would be needed
            if (!is_null($width) && $width > $origW) {
                return null;
            }
            if (!is_null($height) && $height > $origH) {
                return null;
            }

            $image->scaleDown($width, $height)->save($outputPath, quality: 95, progressive: true);

            return $this->fileToUrl($outputPath);
        } catch (\Exception $e) {
            $this->logger->error(
                __('Image resizing failed: %1', $e->getMessage()),
                ['exception' => $e],
            );
        }

        return null;
    }
}
