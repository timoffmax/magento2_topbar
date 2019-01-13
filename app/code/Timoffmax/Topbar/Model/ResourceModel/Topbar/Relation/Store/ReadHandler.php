<?php

namespace Timoffmax\Topbar\Model\ResourceModel\Topbar\Relation\Store;

use Timoffmax\Topbar\Model\ResourceModel\Topbar;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;

/**
 * Class ReadHandler
 */
class ReadHandler implements ExtensionInterface
{
    /**
     * @var Topbar
     */
    protected $resourceTopbar;

    /**
     * @param Topbar $resourceTopbar
     */
    public function __construct(
        Topbar $resourceTopbar
    ) {
        $this->resourceTopbar = $resourceTopbar;
    }

    /**
     * @param object $entity
     * @param array $arguments
     * @return object
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {
        if ($entity->getId()) {
            $stores = $this->resourceTopbar->lookupStoreIds((int)$entity->getId());
            $entity->setData('store_id', $stores);
            $entity->setData('stores', $stores);
        }
        return $entity;
    }
}
