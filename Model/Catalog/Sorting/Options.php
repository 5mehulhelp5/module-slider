<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model\Catalog\Sorting;

use Magento\Framework\Data\OptionSourceInterface;
use Mygento\Slider\Model\Catalog\Sorting;

class Options implements OptionSourceInterface
{
    public function __construct(
        private Sorting $sorting,
    ) {}

    /**
     * @inheritdoc
     */
    public function toOptionArray(): array
    {
        $options = [];
        foreach ($this->sorting->getSortingOptions() as $key => $option) {
            $options[] =
                [
                    'value' => $key,
                    'label' => $option,
                ];
        }

        return $options;
    }
}
