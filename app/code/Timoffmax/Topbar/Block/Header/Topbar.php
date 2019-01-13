<?php

namespace Timoffmax\Topbar\Block\Header;

use Timoffmax\Topbar\Api\Data\TopbarInterface;
use Timoffmax\Topbar\Api\TopbarRepositoryInterface;
use Timoffmax\Topbar\Ui\Component\Listing\Multiselect\Stores;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Topbar extends Template
{
    /** @var TopbarRepositoryInterface */
    private $topbarRepository;

    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /** @var SortOrderBuilder */
    private $sortOrderBuilder;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    public function __construct(
        TopbarRepositoryInterface $topbarRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        ScopeConfigInterface $scopeConfig,
        Context $context,
        array $data = []
    ) {
        $this->topbarRepository = $topbarRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->scopeConfig = $scopeConfig;

        parent::__construct($context, $data);
    }

    /**
     * Get all active topbars for current store view sorted by priority
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAvailableStoreTopbars(): array
    {
        $currentStore = $this->_storeManager->getStore();
        $storeIds = [
            Stores::ALL_STORE_VIEWS,
            $currentStore->getId(),
        ];

        $this->searchCriteriaBuilder->addFilter('is_active', 1);
        $this->searchCriteriaBuilder->addFilter('store_id', $storeIds, 'in');

        $sortOrder = $this->sortOrderBuilder
            ->setField(TopbarInterface::PRIORITY)
            ->setDirection(SortOrder::SORT_ASC)
            ->create();
        $this->searchCriteriaBuilder->addSortOrder($sortOrder);

        $searchCriteria = $this->searchCriteriaBuilder->create();

        $availableTopbars = $this->topbarRepository->getList($searchCriteria);

        return $availableTopbars->getItems();
    }

    /**
     * Get module status
     *
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        return $this->scopeConfig->getValue(
            'timoffmax_topbar/general/enabled',
            ScopeInterface::SCOPE_STORE
        );
    }
}
