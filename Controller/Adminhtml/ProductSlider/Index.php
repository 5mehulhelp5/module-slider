<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Controller\Adminhtml\ProductSlider;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Mygento\Slider\Api\ProductSliderRepositoryInterface;
use Mygento\Slider\Controller\Adminhtml\ProductSlider;

class Index extends ProductSlider
{
    public function __construct(
        private PageFactory $resultPageFactory,
        private DataPersistorInterface $dataPersistor,
        ProductSliderRepositoryInterface $repository,
        Registry $coreRegistry,
        Context $context,
    ) {
        parent::__construct($repository, $coreRegistry, $context);
    }

    /**
     * Index action
     */
    public function execute(): ResultInterface
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage
            ->setActiveMenu('Mygento_Slider::productslider')
            ->getConfig()
            ->getTitle()->prepend(__('Product Slider')->render());

        $this->dataPersistor->clear('slider_productslider');

        return $resultPage;
    }
}
