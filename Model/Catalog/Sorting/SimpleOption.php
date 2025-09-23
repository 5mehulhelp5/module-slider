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

class SimpleOption implements OptionInterface
{
    public function __construct(
        private string $label,
        private ?string $sortDirection = null,
        private ?string $attributeField = null,
        private ?string $secondarySortDirection = null,
    ) {
        $this->secondarySortDirection = $secondarySortDirection ?? $sortDirection;
    }

    /**
     * @inheritdoc
     */
    public function sort(
        Collection $collection,
    ): Collection {
        if ($this->attributeField && $this->sortDirection) {
            $collection->getSelect()->reset(Select::ORDER);
            $collection->addAttributeToSort($this->attributeField, $this->sortDirection);
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
