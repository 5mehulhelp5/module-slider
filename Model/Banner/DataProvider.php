<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model\Banner;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use Mygento\Slider\Model\FileInfo;
use Mygento\Slider\Model\ResourceModel\Banner\Collection;
use Mygento\Slider\Model\ResourceModel\Banner\CollectionFactory;

class DataProvider extends ModifierPoolDataProvider
{
    /** @var Collection */
    protected $collection;

    private DataPersistorInterface $dataPersistor;
    private array $loadedData = [];

    public function __construct(
        private FileInfo $fileInfo,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = [],
        ?PoolInterface $pool = null,
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);

        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
    }

    public function getData(): array
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $this->prepareData($model->getData());
        }

        $data = $this->dataPersistor->get('slider_banner');
        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();

            $model->setData($this->prepareData($data));

            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('slider_banner');
        }

        return $this->loadedData;
    }

    private function prepareData(array $data): array
    {
        $data['image'] = $this->getImageData($data, 'image');
        $data['small_image'] = $this->getImageData($data, 'small_image');

        return $data;
    }

    private function getImageData(array $data, string $key): ?array
    {
        $imageFileName = $data[$key];
        if (!$imageFileName) {
            return null;
        }
        $result = null;

        if ($this->fileInfo->isExist($imageFileName)) {
            $stat = $this->fileInfo->getStat($imageFileName);
            $mime = $this->fileInfo->getMimeType($imageFileName);
            $result = [
                [
                    'name' => $imageFileName,
                    'url' => $this->fileInfo->getUrl($imageFileName),
                    'size' => $stat['size'],
                    'type' => $mime,
                ],
            ];
        }

        return $result;
    }
}
