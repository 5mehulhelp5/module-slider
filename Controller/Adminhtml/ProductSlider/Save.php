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
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use Mygento\Slider\Api\Data\ProductSliderInterface;
use Mygento\Slider\Api\Data\ProductSliderInterfaceFactory;
use Mygento\Slider\Api\ProductSliderRepositoryInterface;
use Mygento\Slider\Controller\Adminhtml\ProductSlider;

class Save extends ProductSlider
{
    public function __construct(
        private Json $serializer,
        private DataPersistorInterface $dataPersistor,
        private ProductSliderInterfaceFactory $entityFactory,
        ProductSliderRepositoryInterface $repository,
        Registry $coreRegistry,
        Context $context,
    ) {
        parent::__construct($repository, $coreRegistry, $context);
    }

    /**
     * Save Product Slider action
     *
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function execute(): ResultInterface
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            return $resultRedirect->setPath('*/*/');
        }
        $entityId = (int) $this->getRequest()->getParam('id');
        $entity = $this->entityFactory->create();
        if ($entityId) {
            try {
                $entity = $this->repository->getById($entityId);
            } catch (NoSuchEntityException $e) {
                if (!$entity->getId()) {
                    $this->messageManager->addErrorMessage(
                        __('This Product Slider no longer exists')->render(),
                    );

                    return $resultRedirect->setPath('*/*/');
                }
            }
        }

        $this->processData($entity, $data);

        try {
            $this->repository->save($entity);
            $this->messageManager->addSuccessMessage(
                __('You saved the Product Slider')->render(),
            );
            $this->dataPersistor->clear('slider_productslider');
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $entity->getId()]);
            }

            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('Something went wrong while saving the Product Slider')->render(),
            );
        }
        $this->dataPersistor->set('slider_productslider', $data);

        return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
    }

    private function processData(ProductSliderInterface $entity, array $data): void
    {
        unset($data['condition_source']);
        if (empty($data['id'])) {
            $data['id'] = null;
        }
        $conditions = $data['parameters']['condition_source'] ?? '';
        $data['parameters']['breakpoints'] = $data['parameters']['breakpoints']['breakpoints'] ?? ($data['parameters']['breakpoints'] ?? []);
        unset($data['parameters']['condition_source']);
        $options = ['options' => $data['options'], 'parameters' => $data['parameters']];

        unset($data['parameters']);
        unset($data['options']);

        $entity->setData($data);
        $entity->setOptions($options);
        $entity->setConditions($this->serializer->serialize($conditions));
    }
}
