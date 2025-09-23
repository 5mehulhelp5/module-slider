<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Mygento\Slider\Api\BannerRepositoryInterface;
use Mygento\Slider\Api\Data\BannerInterface;
use Mygento\Slider\Api\Data\BannerInterfaceFactory;
use Mygento\Slider\Api\Data\BannerSearchResultsInterface;
use Mygento\Slider\Api\Data\BannerSearchResultsInterfaceFactory;
use Mygento\Slider\Model\ResourceModel\Banner\CollectionFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BannerRepository implements BannerRepositoryInterface
{
    public function __construct(
        private ResourceModel\Banner $resource,
        private CollectionFactory $collectionFactory,
        private BannerInterfaceFactory $entityFactory,
        private BannerSearchResultsInterfaceFactory $searchResultsFactory,
        private StoreManagerInterface $storeManager,
        private CollectionProcessorInterface $collectionProcessor,
    ) {}

    /**
     * @throws NoSuchEntityException
     */
    public function getById(int $entityId): BannerInterface
    {
        $entity = $this->entityFactory->create();
        $this->resource->load($entity, $entityId);
        if (!$entity->getId()) {
            throw new NoSuchEntityException(
                __('A Slider Banner with id "%1" does not exist', $entityId),
            );
        }

        return $entity;
    }

    /**
     * @throws CouldNotSaveException
     */
    public function save(BannerInterface $entity): BannerInterface
    {
        if (empty($entity->getStoreId())) {
            $entity->setStoreId([$this->storeManager->getStore()->getId()]);
        }

        try {
            $this->resource->save($entity);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the Slider Banner'),
                $exception,
            );
        }

        return $entity;
    }

    /**
     * @throws CouldNotDeleteException
     */
    public function delete(BannerInterface $entity): bool
    {
        try {
            $this->resource->delete($entity);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __($exception->getMessage()),
            );
        }

        return true;
    }

    /**
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $entityId): bool
    {
        return $this->delete($this->getById($entityId));
    }

    public function getList(SearchCriteriaInterface $criteria): BannerSearchResultsInterface
    {
        /** @var \Mygento\Slider\Model\ResourceModel\Banner\Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var BannerSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
