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
use Mygento\Slider\Api\Data\SliderInterface;
use Mygento\Slider\Api\Data\SliderInterfaceFactory;
use Mygento\Slider\Api\Data\SliderSearchResultsInterface;
use Mygento\Slider\Api\Data\SliderSearchResultsInterfaceFactory;
use Mygento\Slider\Api\SliderRepositoryInterface;
use Mygento\Slider\Model\ResourceModel\Slider\CollectionFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SliderRepository implements SliderRepositoryInterface
{
    public function __construct(
        private ResourceModel\Slider $resource,
        private CollectionFactory $collectionFactory,
        private SliderInterfaceFactory $entityFactory,
        private SliderSearchResultsInterfaceFactory $searchResultsFactory,
        private CollectionProcessorInterface $collectionProcessor,
    ) {}

    /**
     * @throws NoSuchEntityException
     */
    public function getById(int $entityId): SliderInterface
    {
        $entity = $this->entityFactory->create();
        $this->resource->load($entity, $entityId);
        if (!$entity->getId()) {
            throw new NoSuchEntityException(
                __('A Slider Slider with id "%1" does not exist', $entityId),
            );
        }

        return $entity;
    }

    /**
     * @throws CouldNotSaveException
     */
    public function save(SliderInterface $entity): SliderInterface
    {
        try {
            $this->resource->save($entity);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the Slider Slider'),
                $exception,
            );
        }

        return $entity;
    }

    /**
     * @throws CouldNotDeleteException
     */
    public function delete(SliderInterface $entity): bool
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

    public function getList(SearchCriteriaInterface $criteria): SliderSearchResultsInterface
    {
        /** @var \Mygento\Slider\Model\ResourceModel\Slider\Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var SliderSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
