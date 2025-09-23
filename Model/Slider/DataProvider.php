<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model\Slider;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use Mygento\Slider\Api\Data\BannerInterface;
use Mygento\Slider\Api\Data\SliderInterface;
use Mygento\Slider\Model\ResourceModel\Banner;
use Mygento\Slider\Model\ResourceModel\Slider;

class DataProvider extends ModifierPoolDataProvider
{
    /** @var Slider\Collection */
    protected $collection;

    private DataPersistorInterface $dataPersistor;
    private array $loadedData = [];

    public function __construct(
        private Banner\CollectionFactory $bannerCollectionFactory,
        Slider\CollectionFactory $sliderCollectionFactory,
        DataPersistorInterface $dataPersistor,
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        array $meta = [],
        array $data = [],
        ?PoolInterface $pool = null,
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);

        $this->collection = $sliderCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
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
        $data = $this->dataPersistor->get('slider_slider');
        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($this->prepareData($data, $model));
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('slider_slider');
        }

        return $this->loadedData;
    }

    private function prepareData(array $data, SliderInterface $model): array
    {
        $collection = $this->bannerCollectionFactory->create();
        $collection->fetchItemsWithPositionBySlider($data['id']);

        $result = [];
        /** @var BannerInterface $item */
        foreach ($collection as $item) {
            $result[] = [
                'id' => (string) $item->getId(),
                'name' => $item->getName(),
                'from_date' => $item->getFromDate(),
                'to_date' => $item->getToDate(),
                'is_active' => $item->isActive(),
                'position' => (int) $item->getData('position'),
            ];
        }
        $data['slider_items'] = $result;
        $data['options'] = $model->getOptionsList();

        return $data;
    }
}
