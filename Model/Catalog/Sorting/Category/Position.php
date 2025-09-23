<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model\Catalog\Sorting\Category;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\DB\Select;
use Magento\Framework\Phrase;
use Magento\Store\Model\Store;
use Mygento\Slider\Model\Catalog\Sorting\OptionInterface;

class Position implements OptionInterface
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
        $filters = $collection->getLimitationFilters();
        if ($collection->getStoreId() === Store::DEFAULT_STORE_ID && isset($filters['category_id'])) {
            $collection->getSelect()->order("cat_index_position {$this->sortDirection}");
        } else {
            $collection->addAttributeToSort('position', $this->sortDirection);
        }
        if ($this->secondarySortDirection) {
            $collection->addAttributeToSort('entity_id', $this->secondarySortDirection);
        }

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
