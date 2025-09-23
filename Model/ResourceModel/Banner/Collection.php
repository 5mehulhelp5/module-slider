<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model\ResourceModel\Banner;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Mygento\Slider\Api\Data\BannerInterface;
use Mygento\Slider\Model\Banner;
use Mygento\Slider\Model\ResourceModel\Banner as BannerResource;
use Mygento\Slider\Model\ResourceModel\Slider;
use Psr\Log\LoggerInterface;

class Collection extends AbstractCollection
{
    /** @var string */
    protected $_idFieldName = BannerResource::TABLE_PRIMARY_KEY;

    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        private MetadataPool $metadataPool,
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        ?AdapterInterface $connection = null,
        ?AbstractDb $resource = null,
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource,
        );
    }

    public function fetchItemsWithPositionBySlider(int $sliderId): self
    {
        $this->getSelect()->join(
            ['items' => $this->getTable(Slider::TABLE_NAME . '_items')],
            'main_table.' . $this->getResource()->getIdFieldName() . ' = items.banner_id',
            ['position'],
        );
        $this->addFieldToFilter('items.slider_id', $sliderId);
        $this->setOrder('position', 'DESC');
        $this->setOrder('id', 'DESC');

        return $this;
    }

    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init(
            Banner::class,
            BannerResource::class,
        );
    }

    protected function _afterLoad()
    {
        $entityMetadata = $this->metadataPool->getMetadata(BannerInterface::class);
        $linkedIds = $this->getColumnValues($entityMetadata->getLinkField());

        if (!count($linkedIds)) {
            return parent::_afterLoad();
        }

        $connection = $this->getConnection();
        $select = $connection->select()->from(
            ['entity_store' => $this->getTable($this->getMainTable() . '_store')],
        )->where('entity_store.entity_id IN (?)', $linkedIds);

        $result = $connection->fetchAll($select);
        if (!$result) {
            return parent::_afterLoad();
        }

        $stores = [];
        foreach ($result as $r) {
            $stores[$r['entity_id']][] = $r['store_id'];
        }

        foreach ($this as $item) {
            $item->setData('store_id', $stores[$item->getId()]);
        }

        return parent::_afterLoad();
    }

    protected function _renderFiltersBefore()
    {
        if (!$this->getFilter('store_id')) {
            parent::_renderFiltersBefore();

            return;
        }

        $entityMetadata = $this->metadataPool->getMetadata(BannerInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $this->getSelect()->join(
            ['store_table' => $this->getMainTable() . '_store'],
            'main_table.' . $linkField . ' = store_table.entity_id',
            [],
        )->group('main_table.' . $linkField);

        parent::_renderFiltersBefore();
    }
}
