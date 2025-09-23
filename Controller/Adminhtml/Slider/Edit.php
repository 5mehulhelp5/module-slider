<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Mygento\Slider\Api\Data\SliderInterfaceFactory;
use Mygento\Slider\Api\SliderRepositoryInterface;
use Mygento\Slider\Controller\Adminhtml\Slider;

class Edit extends Slider
{
    public function __construct(
        private SliderInterfaceFactory $entityFactory,
        private PageFactory $resultPageFactory,
        SliderRepositoryInterface $repository,
        Registry $coreRegistry,
        Context $context,
    ) {
        parent::__construct($repository, $coreRegistry, $context);
    }

    /**
     * Edit Slider action
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
                    __('This Slider no longer exists')->render(),
                );
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->coreRegistry->register('slider_slider', $entity);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Mygento_Slider::slider');
        $resultPage->addBreadcrumb(
            $entityId ? __('Edit Slider')->render() : __('New Slider')->render(),
            $entityId ? __('Edit Slider')->render() : __('New Slider')->render(),
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Slider')->render());
        $resultPage->getConfig()->getTitle()->prepend(
            $entityId ? $entity->getTitle() : __('New Slider')->render(),
        );

        return $resultPage;
    }
}
