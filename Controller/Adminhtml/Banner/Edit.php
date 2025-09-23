<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Mygento\Slider\Api\BannerRepositoryInterface;
use Mygento\Slider\Api\Data\BannerInterfaceFactory;
use Mygento\Slider\Controller\Adminhtml\Banner;

class Edit extends Banner
{
    public function __construct(
        private BannerInterfaceFactory $entityFactory,
        private PageFactory $resultPageFactory,
        BannerRepositoryInterface $repository,
        Registry $coreRegistry,
        Context $context,
    ) {
        parent::__construct($repository, $coreRegistry, $context);
    }

    /**
     * Edit Banner action
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
                    __('This Banner no longer exists')->render(),
                );
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->coreRegistry->register('slider_banner', $entity);

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Mygento_Slider::banner');
        $resultPage->addBreadcrumb(
            $entityId ? __('Edit Banner')->render() : __('New Banner')->render(),
            $entityId ? __('Edit Banner')->render() : __('New Banner')->render(),
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Banner')->render());
        $resultPage->getConfig()->getTitle()->prepend(
            $entityId ? $entity->getTitle() : __('New Banner')->render(),
        );

        return $resultPage;
    }
}
