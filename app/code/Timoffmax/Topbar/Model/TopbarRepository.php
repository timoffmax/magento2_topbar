<?php

namespace Timoffmax\Topbar\Model;

use Timoffmax\Topbar\Api\TopbarRepositoryInterface;
use Timoffmax\Topbar\Api\Data\TopbarInterface;
use Timoffmax\Topbar\Model\ResourceModel\Topbar as TopbarResourceModel;
use Timoffmax\Topbar\Model\ResourceModel\Topbar\CollectionFactory;
use Timoffmax\Topbar\Api\SearchResultInterface\TopbarSearchResultsInterface;
use Timoffmax\Topbar\Api\SearchResultInterface\TopbarSearchResultsInterfaceFactory;

use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Reflection\DataObjectProcessor;

class TopbarRepository implements TopbarRepositoryInterface
{
    protected $topbarFactory;
    protected $topbarResourceModel;
    protected $collectionFactory;
    protected $searchResultsFactory;
    protected $dataObjectProcessor;

    public function __construct(
        TopbarFactory $topbarFactory,
        TopbarResourceModel $topbarResourceModel,
        CollectionFactory $collectionFactory,
        TopbarSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectProcessor $dataObjectProcessor
    ) {
        $this->topbarFactory = $topbarFactory;
        $this->topbarResourceModel = $topbarResourceModel;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
    }

    /**
     * @param \Timoffmax\Topbar\Api\Data\TopbarInterface $topbar
     * @return \Timoffmax\Topbar\Api\Data\TopbarInterface
     * @throws CouldNotSaveException
     */
    public function save(TopbarInterface $topbar): TopbarInterface
    {
        try {
            $this->topbarResourceModel->save($topbar);
        } catch(\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        return $topbar;
    }

    /**
     * @param int $id
     * @return \Timoffmax\Topbar\Api\Data\TopbarInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): TopbarInterface
    {
        $object = $this->topbarFactory->create();
        $this->topbarResourceModel->load($object, $id);

        if (!$object->getId()) {
            throw new NoSuchEntityException(__('Topbar with id "%1" does not exist.', $id));
        }

        return $object;        
    }

    /**
     * @param \Timoffmax\Topbar\Api\Data\TopbarInterface $topbar
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(TopbarInterface $topbar): bool
    {
        try {
            $this->topbarResourceModel->delete($topbar);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;    
    }

    /**
     * @param int $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $id): bool
    {
        return $this->delete($this->getById($id));
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Timoffmax\Topbar\Api\SearchResultInterface\TopbarSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria): TopbarSearchResultsInterface
    {
//        $searchResults = $this->searchResultsFactory->create();
//        $searchResults->setSearchCriteria($criteria);
//        $collection = $this->collectionFactory->create();
//
//        foreach ($criteria->getFilterGroups() as $filterGroup) {
//            foreach ($filterGroup->getFilters() as $filter) {
//                if ($filter->getField() === 'store_id') {
//                    $collection->addStoreFilter($filter->getValue(), false);
//                    continue;
//                }
//                $condition = $filter->getConditionType() ?: 'eq';
//                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
//            }
//        }
//
//        $searchResults->setTotalCount($collection->getSize());
//        $sortOrders = $criteria->getSortOrders();
//
//        if ($sortOrders) {
//            /** @var SortOrder $sortOrder */
//            foreach ($sortOrders as $sortOrder) {
//                $collection->addOrder(
//                    $sortOrder->getField(),
//                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
//                );
//            }
//        }
//
//        $collection->setCurPage($criteria->getCurrentPage());
//        $collection->setPageSize($criteria->getPageSize());
//        $pages = [];
//
//        foreach ($collection as $objectModel) {
//            $objects[] = $objectModel;
//        }
//
//
////        foreach ($collection as $pageModel) {
////            $pageData = $this->dataPageFactory->create();
////            $this->dataObjectHelper->populateWithArray(
////                $pageData,
////                $pageModel->getData(),
////                'Namespace\Module\Api\Data\YourmodelInterface'
////            );
////            $pages[] = $this->dataObjectProcessor->buildOutputDataArray(
////                $pageData,
////                'Namespace\Module\Api\Data\YourmodelInterface'
////            );
////        }
//
//        $searchResults->setItems($pages);
//
//        return $searchResults;










        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);  
        $collection = $this->collectionFactory->create();

        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];

            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }

                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $fields[] = $filter->getField();
                $conditions[] = [$condition => $filter->getValue()];
            }

            if ($fields) {
                $collection->addFieldToFilter($fields, $conditions);
            }
        }

        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();

        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }

        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $objects = [];

        foreach ($collection as $objectModel) {
            $objects[] = $objectModel;
        }

        $searchResults->setItems($objects);

        return $searchResults;        
    }
}
