<?php

namespace Timoffmax\Topbar\Ui\Component\Listing\DataProviders;

use Timoffmax\Topbar\Model\ResourceModel\Topbar\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class Topbar
 */
class Topbar extends AbstractDataProvider
{
    /**
     * Topbar constructor.
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }
}
