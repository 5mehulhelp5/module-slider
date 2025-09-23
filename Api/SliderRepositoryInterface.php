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
interface SliderRepositoryInterface
{
    /**
     * Save Slider
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Mygento\Slider\Api\Data\SliderInterface
     */
    public function save(Data\SliderInterface $entity): Data\SliderInterface;

    /**
     * Retrieve Slider
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Mygento\Slider\Api\Data\SliderInterface
     */
    public function getById(int $entityId): Data\SliderInterface;

    /**
     * Retrieve Slider entities matching the specified criteria
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Mygento\Slider\Api\Data\SliderSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): Data\SliderSearchResultsInterface;

    /**
     * Delete Slider
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return bool true on success
     */
    public function delete(Data\SliderInterface $entity): bool;

    /**
     * Delete Slider
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return bool true on success
     */
    public function deleteById(int $entityId): bool;
}
