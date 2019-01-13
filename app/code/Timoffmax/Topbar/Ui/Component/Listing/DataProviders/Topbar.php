<?php

namespace Timoffmax\Topbar\Ui\Component\Listing\DataProviders;

use Timoffmax\Topbar\Model\ResourceModel\Topbar\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class Topbar extends AbstractDataProvider
{    
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
