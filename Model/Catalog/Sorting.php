<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model\Catalog;

use Magento\Catalog\Model\ResourceModel\Product\Collection;

class Sorting
{
    private array $sortInstances = [];

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function __construct(
        private array $sortClasses,
        Sorting\Factory $factory,
    ) {
        $this->sortClasses = $sortClasses;
        foreach ($this->sortClasses as $key => $className) {
            $this->sortInstances[$key] = $factory->create($className);
        }
    }

    public function getSortingOptions(): array
    {
        $options = [];
        foreach ($this->sortInstances as $key => $instance) {
            $options[$key] = $instance->getLabel();
        }

        return $options;
    }

    public function getSortingInstance(string $sortOption): ?Sorting\OptionInterface
    {
        if (isset($this->sortInstances[$sortOption])) {
            return $this->sortInstances[$sortOption];
        }

        return null;
    }

    public function applySorting(
        string $option,
        Collection $collection,
    ): Collection {
        $sortBuilder = $this->getSortingInstance($option);
        if ($sortBuilder) {
            $collection = $sortBuilder->sort($collection);
        }

        if ($collection->isLoaded()) {
            $collection->clear();
        }

        return $collection;
    }
}
