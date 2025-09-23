<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model\ResourceModel\ProductSlider\Relation\Store;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Mygento\Slider\Api\Data\ProductSliderInterface;
use Mygento\Slider\Model\ResourceModel\ProductSlider;

class ReadHandler implements ExtensionInterface
{
    public function __construct(
        private ProductSlider $resource,
    ) {}

    /**
     * @inheritDoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {
        if ($entity->getId()) {
            $stores = $this->resource->lookupStoreIds((int) $entity->getId());
            $entity->setData(ProductSliderInterface::STORE_ID, $stores);
        }

        return $entity;
    }
}
