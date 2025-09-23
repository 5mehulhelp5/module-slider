<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Controller\Adminhtml\Slider;

use Magento\Framework\Controller\ResultInterface;
use Mygento\Slider\Controller\Adminhtml\Slider;

class Delete extends Slider
{
    /**
     * Delete Slider action
     */
    public function execute(): ResultInterface
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $entityId = (int) $this->getRequest()->getParam('id');
        if (!$entityId) {
            $this->messageManager->addErrorMessage(
                __('We can not find a Slider to delete.')->render(),
            );

            return $resultRedirect->setPath('*/*/');
        }

        try {
            $this->repository->deleteById($entityId);
            $this->messageManager->addSuccessMessage(
                __('You deleted the Slider')->render(),
            );

            return $resultRedirect->setPath('*/*/');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('*/*/edit', ['id' => $entityId]);
    }
}
