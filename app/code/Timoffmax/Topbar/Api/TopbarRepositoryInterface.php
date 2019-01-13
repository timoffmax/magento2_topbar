<?php

namespace Timoffmax\Topbar\Api;

use Timoffmax\Topbar\Api\Data\TopbarInterface;
use Timoffmax\Topbar\Api\SearchResultInterface\TopbarSearchResultsInterface;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface TopbarRepositoryInterface
 */
interface TopbarRepositoryInterface
{
    /**
     * @param \Timoffmax\Topbar\Api\Data\TopbarInterface $topbar
     * @return \Timoffmax\Topbar\Api\Data\TopbarInterface
     */
    public function save(TopbarInterface $topbar): TopbarInterface;

    /**
     * @param int $id
     * @return \Timoffmax\Topbar\Api\Data\TopbarInterface
     */
    public function getById(int $id): ?TopbarInterface;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Timoffmax\Topbar\Api\SearchResultInterface\TopbarSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): TopbarSearchResultsInterface;

    /**
     * @param \Timoffmax\Topbar\Api\Data\TopbarInterface $topbar
     * @return bool
     */
    public function delete(TopbarInterface $topbar): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool;
}
