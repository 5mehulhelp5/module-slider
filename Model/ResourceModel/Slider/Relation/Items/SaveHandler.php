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

class SaveHandler implements ExtensionInterface
{
    public function __construct(
        private Slider $resource,
    ) {}

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param SliderInterface $entity
     * @param array $arguments
     */
    public function execute($entity, $arguments = []): SliderInterface
    {
        $connection = $this->resource->getConnection();
        $relationTable = $this->resource->getTable($this->resource->getMainTable() . '_items');

        $oldBanners = $this->resource->fetchCurrentRelations($entity->getId());
        $newBanners = $this->convertBanners($entity->getData('slider_items') ?? []);

        // Insert
        $insert = array_diff_key($newBanners, $oldBanners);
        if ($insert) {
            $data = [];
            foreach ($insert as $id => $v) {
                $data[] = [
                    'slider_id' => $entity->getId(),
                    'banner_id' => $id,
                    'position' => $v,
                ];
            }
            $connection->insertMultiple($relationTable, $data);
        }
        // Update
        foreach ($newBanners as $id => $position) {
            if (isset($oldBanners[$id]) && $oldBanners[$id] != $position) {
                $connection->update(
                    $relationTable,
                    ['position' => $position],
                    [
                        'slider_id = ?' => $entity->getId(),
                        'banner_id = ?' => $id,
                    ],
                );
            }
        }
        // Delete
        $delete = array_diff_key($oldBanners, $newBanners);
        if ($delete) {
            $where = [
                'slider_id = ?' => $entity->getId(),
                'banner_id IN (?)' => array_keys($delete),
            ];
            $connection->delete($relationTable, $where);
        }

        return $entity;
    }

    private function convertBanners(array $banners): array
    {
        $result = [];
        foreach ($banners as $b) {
            $result[$b['banner_id']] = $b['position'];
        }

        return $result;
    }
}
