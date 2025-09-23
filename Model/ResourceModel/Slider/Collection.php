<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model\ResourceModel\Slider;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mygento\Slider\Model\ResourceModel\Slider as SliderResource;
use Mygento\Slider\Model\Slider;

class Collection extends AbstractCollection
{
    /** @var string */
    protected $_idFieldName = SliderResource::TABLE_PRIMARY_KEY;

    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init(
            Slider::class,
            SliderResource::class,
        );
    }
}
