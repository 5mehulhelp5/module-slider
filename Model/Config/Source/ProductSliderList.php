<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model\Config\Source;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;
use Mygento\Slider\Api\Data\ProductSliderInterface;
use Mygento\Slider\Api\ProductSliderRepositoryInterface;

class ProductSliderList implements OptionSourceInterface
{
    public function __construct(
        private ProductSliderRepositoryInterface $repo,
        private SearchCriteriaBuilder $builder,
    ) {}

    public function toOptionArray(): array
    {
        $sliders = $this->repo->getList($this->builder->create());

        $optionArray = [];
        /** @var ProductSliderInterface $slider */
        foreach ($sliders->getItems() as $slider) {
            $optionArray[] = [
                'value' => $slider->getId(),
                'label' => $slider->getIdentity(),
            ];
        }

        return $optionArray;
    }
}
