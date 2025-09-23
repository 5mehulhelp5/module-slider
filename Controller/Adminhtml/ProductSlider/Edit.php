<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Controller\Adminhtml\ProductSlider;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Mygento\Slider\Api\Data\ProductSliderInterfaceFactory;
use Mygento\Slider\Api\ProductSliderRepositoryInterface;
use Mygento\Slider\Controller\Adminhtml\ProductSlider;

class Edit extends ProductSlider
{
    public function __construct(
        private ProductSliderInterfaceFactory $entityFactory,
        private PageFactory $resultPageFactory,
        ProductSliderRepositoryInterface $repository,
        Registry $coreRegistry,
        Context $context,
    ) {
        parent::__construct($repository, $coreRegistry, $context);
    }

    /**
     * Edit Product Slider action
     */
    public function execute(): ResultInterface
    {
        $entityId = (int) $this->getRequest()->getParam('id');
        $entity = $this->entityFactory->create();
        if ($entityId) {
            try {
                $entity = $this->repository->getById($entityId);
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(
                    __('This Product Slider no longer exists')->render(),
                );
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->coreRegistry->register('slider_productslider', $entity);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Mygento_Slider::productslider');
        $resultPage->addBreadcrumb(
            $entityId ? __('Edit Product Slider')->render() : __('New Product Slider')->render(),
            $entityId ? __('Edit Product Slider')->render() : __('New Product Slider')->render(),
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Product Slider')->render());
        $resultPage->getConfig()->getTitle()->prepend(
            $entityId ? $entity->getTitle() : __('New Product Slider')->render(),
        );

        return $resultPage;
    }
}
