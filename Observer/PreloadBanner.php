<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Page\Config;
use Mygento\Slider\Block\BannerSlider;

class PreloadBanner implements ObserverInterface
{
    public function __construct(private Config $pageConfig)
    {
        $this->pageConfig = $pageConfig;
    }

    public function execute(Observer $observer): void
    {
        $layout = $observer->getEvent()->getLayout();
        foreach ($layout->getAllBlocks() as $block) {
            if (!($block instanceof BannerSlider)) {
                continue;
            }
            $block->appendPreload();
        }
    }
}
