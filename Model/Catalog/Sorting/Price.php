<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model\Catalog\Sorting;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\DB\Select;
use Magento\Framework\Phrase;

/**
 * Sort catalog products by price
 */
class Price implements OptionInterface
{
    public function __construct(
        private string $label,
        private string $sortDirection = Select::SQL_ASC,
        private ?string $secondarySortDirection = null,
    ) {
        $this->secondarySortDirection = $secondarySortDirection ?? $sortDirection;
    }

    /**
     * @inheritDoc
     */
    public function sort(Collection $collection): Collection
    {
        $collection->getSelect()->reset(Select::ORDER);
        if ($collection->getLimitationFilters()->isUsingPriceIndex()) {
            $collection->getSelect()->order("price_index.min_price {$this->sortDirection}");
        } else {
            $collection->addAttributeToSort('price', $this->sortDirection);
        }
        $collection->addAttributeToSort('entity_id', $this->secondarySortDirection);

        return $collection;
    }

    /**
     * @inheritdoc
     */
    public function getLabel(): Phrase
    {
        return __($this->label);
    }
}
