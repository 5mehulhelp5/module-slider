<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Mygento\Slider\Api\BannerRepositoryInterface;
use Mygento\Slider\Controller\Adminhtml\Banner;

class Index extends Banner
{
    public function __construct(
        private PageFactory $resultPageFactory,
        private DataPersistorInterface $dataPersistor,
        BannerRepositoryInterface $repository,
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
            ->setActiveMenu('Mygento_Slider::banner')
            ->getConfig()
            ->getTitle()->prepend(__('Banner')->render());

        $this->dataPersistor->clear('slider_banner');

        return $resultPage;
    }
}
