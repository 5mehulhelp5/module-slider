<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\Filesystem\ExtendedDriverInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class FileInfo
{
    private ?WriteInterface $mediaDirectory = null;

    public function __construct(
        private Filesystem $filesystem,
        private Mime $mime,
        private StoreManagerInterface $storeManager,
    ) {}

    public function getUrl(string $fileName): string
    {
        $mediaBaseUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        return rtrim($mediaBaseUrl, '/') . '/' . $fileName;
    }

    public function isExist(string $fileName): bool
    {
        $filePath = $this->getFilePath($fileName);

        return $this->getMediaDirectory()->isExist($filePath);
    }

    public function getStat($fileName): array
    {
        $filePath = $this->getFilePath($fileName);

        return $this->getMediaDirectory()->stat($filePath);
    }

    public function getMimeType($fileName)
    {
        if ($this->getMediaDirectory()->getDriver() instanceof ExtendedDriverInterface) {
            return $this->mediaDirectory->getDriver()->getMetadata($fileName)['mimetype'];
        }

        return $this->mime->getMimeType(
            $this->getMediaDirectory()->getAbsolutePath(
                $this->getFilePath($fileName),
            ),
        );
    }

    private function getFilePath(string $fileName): string
    {
        return ltrim($fileName, '/');
    }

    private function getMediaDirectory(): WriteInterface
    {
        if ($this->mediaDirectory === null) {
            $this->mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        }

        return $this->mediaDirectory;
    }
}
