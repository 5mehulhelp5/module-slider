<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model\Catalog\Sorting;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Phrase;

interface OptionInterface
{
    /**
     * Sort products in product widget collection according to specified option.
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function sort(
        Collection $collection,
    ): Collection;

    /**
     * Sorting option short description
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel(): Phrase;
}
