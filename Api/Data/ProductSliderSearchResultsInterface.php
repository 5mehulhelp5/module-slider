<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ProductSliderSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get list of Product Slider
     * @return \Mygento\Slider\Api\Data\ProductSliderInterface[]
     */
    public function getItems();

    /**
     * Set list of Product Slider
     * @param \Mygento\Slider\Api\Data\ProductSliderInterface[] $items
     */
    public function setItems(array $items);
}
