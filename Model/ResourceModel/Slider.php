<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model\ResourceModel;

use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class Slider extends AbstractDb
{
    public const TABLE_NAME = 'mygento_slider';
    public const TABLE_PRIMARY_KEY = 'id';

    public function __construct(
        private EntityManager $entityManager,
        Context $context,
        ?string $connectionName = null,
    ) {
        parent::__construct($context, $connectionName);
    }

    public function fetchCurrentRelations(int $id): array
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from(['ei' => $this->getMainTable() . '_items'], ['banner_id', 'position'])
            ->join(
                ['e' => $this->getMainTable()],
                'ei.slider_id = e.id',
                [],
            )
            ->where('e.' . $this->getIdFieldName() . ' = :entity_id');

        return $connection->fetchAssoc($select, ['entity_id' => (int) $id]);
    }

    /**
     * @inheritDoc
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        return $this->entityManager->load($object, $value);
    }

    /**
     * @inheritDoc
     */
    public function save(AbstractModel $object)
    {
        $this->entityManager->save($object);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);

        return $this;
    }

    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::TABLE_PRIMARY_KEY);
    }
}
