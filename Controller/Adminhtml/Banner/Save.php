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
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;
use Mygento\Slider\Api\BannerRepositoryInterface;
use Mygento\Slider\Api\Data\BannerInterface;
use Mygento\Slider\Api\Data\BannerInterfaceFactory;
use Mygento\Slider\Controller\Adminhtml\Banner;
use Mygento\Slider\Model\Uploader;

class Save extends Banner
{
    public function __construct(
        private DataPersistorInterface $dataPersistor,
        private BannerInterfaceFactory $entityFactory,
        private Uploader $imageUploader,
        private DateTime $dateTime,
        BannerRepositoryInterface $repository,
        Registry $coreRegistry,
        Context $context,
    ) {
        parent::__construct($repository, $coreRegistry, $context);
    }

    /**
     * Save Banner action
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
                        __('This Banner no longer exists')->render(),
                    );

                    return $resultRedirect->setPath('*/*/');
                }
            }
        }

        $this->normalizeData($entity, $data);

        try {
            $entity->setImage($this->processImage($data, 'image') ?? '');
            $entity->setSmallImage($this->processImage($data, 'small_image'));
            $this->repository->save($entity);
            $this->messageManager->addSuccessMessage(
                __('You saved the Banner')->render(),
            );
            $this->dataPersistor->clear('slider_banner');
            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $entity->getId()]);
            }

            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('Something went wrong while saving the Banner')->render(),
            );
        }
        $this->dataPersistor->set('slider_banner', $data);

        return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
    }

    private function processImage(array $data, string $key): ?string
    {
        $value = $data[$key] ?? null;
        if (!$value) {
            return null;
        }
        if (!is_array($value)) {
            return null;
        }
        $imageName = $value['0']['name'] ?? null;
        if (!$imageName) {
            return null;
        }
        if ($imageName && !isset($value[0]['tmp_name'])) {
            return $imageName;
        }

        return $this->imageUploader->moveFileFromTmp($imageName);
    }

    private function normalizeData(BannerInterface $entity, array $data): void
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

        $data = $this->normalizeDate(
            'to_date',
            $this->normalizeDate('from_date', $data),
        );
        // TODO: DELETE OLD IMAGE IF SET NULL
        $entity->setData($data);
    }

    private function normalizeDate(string $key, array $data): array
    {
        if ($data[$key] !== null) {
            $data[$key] = $this->dateTime->formatDate($data[$key]);
        }

        return $data;
    }
}
