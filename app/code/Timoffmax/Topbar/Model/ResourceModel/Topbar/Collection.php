<?php

namespace Timoffmax\Topbar\Model\ResourceModel\Topbar;

use Timoffmax\Topbar\Api\Data\TopbarInterface;
use Timoffmax\Topbar\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'topbar_id';

    protected function _construct()
    {
        $this->_init(
            'Timoffmax\Topbar\Model\Topbar',
            'Timoffmax\Topbar\Model\ResourceModel\Topbar'
        );
        $this->_map['fields']['topbar_id'] = 'main_table.topbar_id';
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $entityMetadata = $this->metadataPool->getMetadata(TopbarInterface::class);

        $this->performAfterLoad('timoffmax_topbar_store', $entityMetadata->getLinkField());

        return parent::_afterLoad();
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        $this->performAddStoreFilter($store, $withAdmin);

        return $this;
    }

    /**
     * Join store relation table if there is store filter
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        $entityMetadata = $this->metadataPool->getMetadata(TopbarInterface::class);
        $this->joinStoreRelationTable('timoffmax_topbar_store', $entityMetadata->getLinkField());
    }
}
