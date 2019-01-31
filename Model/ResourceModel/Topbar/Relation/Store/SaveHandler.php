<?php

namespace Timoffmax\Topbar\Model\ResourceModel\Topbar\Relation\Store;

use Timoffmax\Topbar\Model\ResourceModel\Topbar;
use Timoffmax\Topbar\Api\Data\TopbarInterface;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Magento\Framework\EntityManager\MetadataPool;

/**
 * Class SaveHandler
 */
class SaveHandler implements ExtensionInterface
{
    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @var Topbar
     */
    protected $resourceTopbar;

    /**
     * @param MetadataPool $metadataPool
     * @param Topbar $resourceTopbar
     */
    public function __construct(
        MetadataPool $metadataPool,
        Topbar $resourceTopbar
    ) {
        $this->metadataPool = $metadataPool;
        $this->resourceTopbar = $resourceTopbar;
    }

    /**
     * @param object $entity
     * @param array $arguments
     * @return object
     * @throws \Exception
     */
    public function execute($entity, $arguments = [])
    {
        $entityMetadata = $this->metadataPool->getMetadata(TopbarInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $connection = $entityMetadata->getEntityConnection();

        $oldStores = $this->resourceTopbar->lookupStoreIds((int)$entity->getId());
        $newStores = (array)$entity->getStores();

        $table = $this->resourceTopbar->getTable('timoffmax_topbar_store');

        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = [
                $linkField . ' = ?' => (int)$entity->getData($linkField),
                'store_id IN (?)' => $delete,
            ];
            $connection->delete($table, $where);
        }

        $insert = array_diff($newStores, $oldStores);

        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    $linkField => (int)$entity->getData($linkField),
                    'store_id' => (int)$storeId,
                ];
            }
            $connection->insertMultiple($table, $data);
        }

        return $entity;
    }
}
