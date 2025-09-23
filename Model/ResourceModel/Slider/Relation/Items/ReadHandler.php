<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model\ResourceModel\Slider\Relation\Items;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Mygento\Slider\Api\Data\SliderInterface;
use Mygento\Slider\Model\ResourceModel\Slider;

class ReadHandler implements ExtensionInterface
{
    public function __construct(
        private Slider $resource,
    ) {}

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @param SliderInterface $entity
     * @param array $arguments
     */
    public function execute($entity, $arguments = []): SliderInterface
    {
        if ($entity->getId()) {
            $data = $this->resource->fetchCurrentRelations($entity->getId());
            $entity->setData('slider_items', $data);
        }

        return $entity;
    }
}
