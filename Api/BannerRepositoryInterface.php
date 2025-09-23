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
interface BannerRepositoryInterface
{
    /**
     * Save Banner
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Mygento\Slider\Api\Data\BannerInterface
     */
    public function save(Data\BannerInterface $entity): Data\BannerInterface;

    /**
     * Retrieve Banner
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Mygento\Slider\Api\Data\BannerInterface
     */
    public function getById(int $entityId): Data\BannerInterface;

    /**
     * Retrieve Banner entities matching the specified criteria
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return \Mygento\Slider\Api\Data\BannerSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): Data\BannerSearchResultsInterface;

    /**
     * Delete Banner
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return bool true on success
     */
    public function delete(Data\BannerInterface $entity): bool;

    /**
     * Delete Banner
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return bool true on success
     */
    public function deleteById(int $entityId): bool;
}
