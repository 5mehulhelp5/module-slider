<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model\ProductSlider;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use Mygento\Slider\Api\Data\ProductSliderInterface;
use Mygento\Slider\Model\ResourceModel\ProductSlider\Collection;
use Mygento\Slider\Model\ResourceModel\ProductSlider\CollectionFactory;

class DataProvider extends ModifierPoolDataProvider
{
    /** @var Collection */
    protected $collection;

    private array $loadedData = [];

    public function __construct(
        private DataPersistorInterface $dataPersistor,
        CollectionFactory $collectionFactory,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = [],
        ?PoolInterface $pool = null,
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);

        $this->collection = $collectionFactory->create();
    }

    public function getData(): array
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $this->prepareData($model->getData(), $model);
        }
        $data = $this->dataPersistor->get('slider_productslider');
        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($this->prepareData($data, $model));
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('slider_productslider');
        }

        return $this->loadedData;
    }

    private function prepareData(array $data, ProductSliderInterface $model): array
    {
        unset($data['conditions']);
        unset($data['options']);
        $data['parameters'] = $model->getOptions()['parameters'] ?? [];
        $data['options'] = $model->getOptions()['options'] ?? [];
        $data['condition_source'] = $model->getConditions();

        return $data;
    }
}
