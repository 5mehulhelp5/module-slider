<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * @api
 */
interface ProductSliderRepositoryInterface
{
    /**
     * Save Product Slider
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Mygento\Slider\Api\Data\ProductSliderInterface
     */
    public function save(Data\ProductSliderInterface $entity): Data\ProductSliderInterface;

    /**
     * Retrieve Product Slider
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Mygento\Slider\Api\Data\ProductSliderInterface
     */
    public function getById(int $entityId): Data\ProductSliderInterface;

    /**
     * Retrieve Product Slider entities matching the specified criteria
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Mygento\Slider\Api\Data\ProductSliderSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): Data\ProductSliderSearchResultsInterface;

    /**
     * Delete Product Slider
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return bool true on success
     */
    public function delete(Data\ProductSliderInterface $entity): bool;

    /**
     * Delete Product Slider
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return bool true on success
     */
    public function deleteById(int $entityId): bool;
}
