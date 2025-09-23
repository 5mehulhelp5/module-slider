<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface BannerSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get list of Banner
     * @return \Mygento\Slider\Api\Data\BannerInterface[]
     */
    public function getItems();

    /**
     * Set list of Banner
     * @param \Mygento\Slider\Api\Data\BannerInterface[] $items
     */
    public function setItems(array $items);
}
