<?php

namespace Timoffmax\Topbar\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Timoffmax\Topbar\Api\Data\TopbarInterface;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DB\Select;
use Magento\Store\Model\Store;

class Topbar extends AbstractDb
{
    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param EntityManager $entityManager
     * @param MetadataPool $metadataPool
     * @param string $connectionName
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        EntityManager $entityManager,
        MetadataPool $metadataPool,
        $connectionName = null
    ) {
        $this->_storeManager = $storeManager;
        $this->entityManager = $entityManager;
        $this->metadataPool = $metadataPool;
        parent::__construct($context, $connectionName);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('timoffmax_topbar','topbar_id');
    }

    /**
     * Perform operations before object save
     *
     * @param AbstractModel $object
     * @return $this
     * @throws LocalizedException
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if (!$this->getIsUniqueTopbarToStores($object)) {
            throw new LocalizedException(
                __('A topbar identifier with the same properties already exists in the selected store.')
            );
        }

        return $this;
    }

    /**
     * @return false|\Magento\Framework\DB\Adapter\AdapterInterface
     * @throws \Exception
     */
    public function getConnection()
    {
        return $this->metadataPool->getMetadata(TopbarInterface::class)->getEntityConnection();

    }

    /**
     * @param AbstractModel $object
     * @param $value
     * @param null $field
     * @return bool|int|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getTopbarId(AbstractModel $object, $value, $field = null)
    {
        $entityMetadata = $this->metadataPool->getMetadata(TopbarInterface::class);

        if (!is_numeric($value) && $field === null) {
            $field = TopbarInterface::ID;
        } elseif (!$field) {
            $field = $entityMetadata->getIdentifierField();
        }

        $topbarId = $value;

        if ($field != $entityMetadata->getIdentifierField() || $object->getStoreId()) {
            $select = $this->_getLoadSelect($field, $value, $object);
            $select->reset(Select::COLUMNS)
                ->columns($this->getMainTable() . '.' . $entityMetadata->getIdentifierField())
                ->limit(1);
            $result = $this->getConnection()->fetchCol($select);
            $topbarId = count($result) ? $result[0] : false;
        }

        return $topbarId;
    }

    /**
     * @param AbstractModel $object
     * @param mixed $value
     * @param null $field
     * @return $this|AbstractDb
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        $topbarId = $this->getTopbarId($object, $value, $field);

        if ($topbarId) {
            $this->entityManager->load($object, $topbarId);
        }

        return $this;
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param AbstractModel $object
     * @return Select
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $entityMetadata = $this->metadataPool->getMetadata(TopbarInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $stores = [(int)$object->getStoreId(), Store::DEFAULT_STORE_ID];

            $select->join(
                ['tts' => $this->getTable('timoffmax_topbar_store')],
                $this->getMainTable() . '.' . $linkField . ' = tts.' . $linkField,
                ['store_id']
            )
                ->where('is_active = ?', 1)
                ->where('tts.store_id in (?)', $stores)
                ->order('store_id DESC')
                ->limit(1);
        }

        return $select;
    }

    /**
     * Check for unique of identifier of topbar to selected store(s).
     *
     * @param AbstractModel $object
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsUniqueTopbarToStores(AbstractModel $object)
    {
        $entityMetadata = $this->metadataPool->getMetadata(TopbarInterface::class);
        $linkField = $entityMetadata->getLinkField();

        if ($this->_storeManager->isSingleStoreMode()) {
            $stores = [Store::DEFAULT_STORE_ID];
        } else {
            $stores = (array)$object->getData('store_id');
        }

        $select = $this->getConnection()->select()
            ->from(['tt' => $this->getMainTable()])
            ->join(
                ['tts' => $this->getTable('timoffmax_topbar_store')],
                'tt.' . $linkField . ' = tts.' . $linkField,
                []
            )
            ->where('tt.identifier = ?', $object->getData('identifier'))
            ->where('tts.store_id IN (?)', $stores);

        if ($object->getId()) {
            $select->where('tt.' . $entityMetadata->getIdentifierField() . ' <> ?', $object->getId());
        }

        if ($this->getConnection()->fetchRow($select)) {
            return false;
        }

        return true;
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($id)
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->metadataPool->getMetadata(TopbarInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
            ->from(['tts' => $this->getTable('timoffmax_topbar_store')], 'store_id')
            ->join(
                ['tt' => $this->getMainTable()],
                'tts.' . $linkField . ' = tt.' . $linkField,
                []
            )
            ->where('tt.' . $entityMetadata->getIdentifierField() . ' = :id');

        return $connection->fetchCol($select, ['id' => (int)$id]);
    }

    public function setStore($store)
    {
        $this->_store = $store;

        return $this;
    }

    public function getStore()
    {
        return $this->_storeManager->getStore($this->_store);
    }

    public function save(AbstractModel $object)
    {
        $this->entityManager->save($object);
        return $this;
    }

    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);
        return $this;
    }
}
