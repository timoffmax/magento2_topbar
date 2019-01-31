<?php

namespace Timoffmax\Topbar\Api\SearchResultInterface;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface TopbarSearchResultsInterface
 * @api
 */
interface TopbarSearchResultsInterface extends SearchResultsInterface
{
    /**
     * @return \Timoffmax\Topbar\Api\Data\TopbarInterface[]
     */
    public function getItems();

    /**
     * @param \Timoffmax\Topbar\Api\Data\TopbarInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
