<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface SliderSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get list of Slider
     * @return \Mygento\Slider\Api\Data\SliderInterface[]
     */
    public function getItems();

    /**
     * Set list of Slider
     * @param \Mygento\Slider\Api\Data\SliderInterface[] $items
     */
    public function setItems(array $items);
}
