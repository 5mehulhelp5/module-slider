<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Ui\Component\Form\Slider;

use Mygento\Slider\Model\ProductSlider;

class ProductOptions extends BannerOptions
{
    protected function getList(): array
    {
        return ProductSlider::getSliderOptions();
    }
}
