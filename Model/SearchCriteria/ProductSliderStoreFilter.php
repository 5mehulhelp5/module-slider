<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model\SearchCriteria;

use Magento\Framework\Api\Filter;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor\FilterProcessor\CustomFilterInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Mygento\Slider\Api\Data\ProductSliderInterface;

class ProductSliderStoreFilter implements CustomFilterInterface
{
    /**
     * @inheritDoc
     */
    public function apply(Filter $filter, AbstractDb $collection): bool
    {
        $collection->addFilter(
            ProductSliderInterface::STORE_ID,
            ['in' => $filter->getValue()],
        );

        return true;
    }
}
