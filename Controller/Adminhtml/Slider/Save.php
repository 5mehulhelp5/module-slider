<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Controller\Adminhtml\Slider;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;
use Mygento\Slider\Api\Data\SliderInterface;
use Mygento\Slider\Api\Data\SliderInterfaceFactory;
use Mygento\Slider\Api\SliderRepositoryInterface;
use Mygento\Slider\Controller\Adminhtml\Slider;

class Save extends Slider
{
    public function __construct(
        private DataPersistorInterface $dataPersistor,
        private SliderInterfaceFactory $entityFactory,
        private DateTime $dateTime,
        SliderRepositoryInterface $repository,
        Registry $coreRegistry,
        Context $context,
    ) {
        parent::__construct($repository, $coreRegistry, $context);
    }

    /**
     * Save Slider action
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
                        __('This Slider no longer exists')->render(),
                    );

                    return $resultRedirect->setPath('*/*/');
                }
            }
        }

        $this->normalizeData($entity, $data);

        try {
            $this->repository->save($entity);
            $this->messageManager->addSuccessMessage(
                __('You saved the Slider')->render(),
            );
            $this->dataPersistor->clear('slider_slider');
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $entity->getId()]);
            }

            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('Something went wrong while saving the Slider')->render(),
            );
        }
        $this->dataPersistor->set('slider_slider', $data);

        return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
    }

    private function normalizeData(SliderInterface $entity, array $data): void
    {
        if (empty($data['id'])) {
            $data['id'] = null;
        }
        if (empty($data['from_date'])) {
            $data['from_date'] = null;
        }
        if (empty($data['to_date'])) {
            $data['to_date'] = null;
        }

        $options = $data['options'];
        unset($data['options']);

        $data = $this->normalizeDate(
            'to_date',
            $this->normalizeDate('from_date', $data),
        );

        $entity->setData($data);
        $entity->setOptions($options);
    }

    private function normalizeDate(string $key, array $data): array
    {
        if ($data[$key] !== null) {
            $data[$key] = $this->dateTime->formatDate($data[$key]);
        }

        return $data;
    }
}
