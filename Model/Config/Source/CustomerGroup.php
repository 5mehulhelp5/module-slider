<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model\Config\Source;

use Magento\Customer\Api\Data\GroupInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Data\OptionSourceInterface;

class CustomerGroup implements OptionSourceInterface
{
    public function __construct(
        private GroupRepositoryInterface $repo,
        private SearchCriteriaBuilder $builder,
    ) {}

    public function toOptionArray(): array
    {
        $groups = $this->repo->getList($this->builder->create());

        $optionArray = [];
        /** @var GroupInterface $group */
        foreach ($groups->getItems() as $group) {
            $optionArray[] = [
                'value' => $group->getId(),
                'label' => $group->getCode(),
            ];
        }

        return $optionArray;
    }
}
