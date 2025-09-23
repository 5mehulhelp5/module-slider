<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Mygento\Slider\Api\BannerRepositoryInterface;

abstract class Banner extends Action
{
    /**
     * Authorization level
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Mygento_Slider::banner';

    public function __construct(
        protected BannerRepositoryInterface $repository,
        protected Registry $coreRegistry,
        Action\Context $context,
    ) {
        parent::__construct($context);
    }
}
