<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model\Catalog\Sorting;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\ObjectManagerInterface;

class Factory
{
    public function __construct(private ObjectManagerInterface $om) {}

    public function create(string $className, array $data = []): OptionInterface
    {
        $instance = $this->om->create($className, $data);

        if (!$instance instanceof OptionInterface) {
            throw new LocalizedException(
                __('%1 doesn\'t implement OptionInterface', $className),
            );
        }

        return $instance;
    }
}
