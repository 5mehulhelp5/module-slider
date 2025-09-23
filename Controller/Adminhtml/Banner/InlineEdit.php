<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime;
use Mygento\Slider\Api\BannerRepositoryInterface;
use Mygento\Slider\Api\Data\BannerInterface;
use Mygento\Slider\Controller\Adminhtml\Banner;

class InlineEdit extends Banner
{
    public function __construct(
        private JsonFactory $jsonFactory,
        private DateTime $dateTime,
        BannerRepositoryInterface $repository,
        Registry $coreRegistry,
        Context $context,
    ) {
        parent::__construct($repository, $coreRegistry, $context);
    }

    /**
     * Execute action
     */
    public function execute(): ResultInterface
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')->render()],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $id) {
            try {
                $entity = $this->repository->getById($id);
                $this->normalizeData($entity, array_merge($entity->getData(), $postItems[$id]));
                $this->repository->save($entity);
            } catch (NoSuchEntityException $e) {
                $messages[] = $id . ' -> ' . __('Not found')->render();
                $error = true;
                continue;
            } catch (\Exception $e) {
                $messages[] = __($e->getMessage());
                $error = true;
                continue;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error,
        ]);
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
