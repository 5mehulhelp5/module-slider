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
use Mygento\Slider\Api\Data\ProductSliderInterface;
use Mygento\Slider\Api\Data\ProductSliderInterfaceFactory;
use Mygento\Slider\Api\Data\ProductSliderSearchResultsInterface;
use Mygento\Slider\Api\Data\ProductSliderSearchResultsInterfaceFactory;
use Mygento\Slider\Api\ProductSliderRepositoryInterface;
use Mygento\Slider\Model\ResourceModel\ProductSlider\CollectionFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ProductSliderRepository implements ProductSliderRepositoryInterface
{
    public function __construct(
        private ResourceModel\ProductSlider $resource,
        private CollectionFactory $collectionFactory,
        private ProductSliderInterfaceFactory $entityFactory,
        private ProductSliderSearchResultsInterfaceFactory $searchResultsFactory,
        private StoreManagerInterface $storeManager,
        private CollectionProcessorInterface $collectionProcessor,
    ) {}

    /**
     * @throws NoSuchEntityException
     */
    public function getById(int $entityId): ProductSliderInterface
    {
        $entity = $this->entityFactory->create();
        $this->resource->load($entity, $entityId);
        if (!$entity->getId()) {
            throw new NoSuchEntityException(
                __('A Slider Product Slider with id "%1" does not exist', $entityId),
            );
        }

        return $entity;
    }

    /**
     * @throws CouldNotSaveException
     */
    public function save(ProductSliderInterface $entity): ProductSliderInterface
    {
        if (empty($entity->getStoreId())) {
            $entity->setStoreId([$this->storeManager->getStore()->getId()]);
        }

        try {
            $this->resource->save($entity);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the Slider Product Slider'),
                $exception,
            );
        }

        return $entity;
    }

    /**
     * @throws CouldNotDeleteException
     */
    public function delete(ProductSliderInterface $entity): bool
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

    public function getList(SearchCriteriaInterface $criteria): ProductSliderSearchResultsInterface
    {
        /** @var \Mygento\Slider\Model\ResourceModel\ProductSlider\Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var ProductSliderSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
