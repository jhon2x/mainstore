<?php

namespace Brownbag\Presentation\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchResultInterface;
use Brownbag\Presentation\Model\ResourceModel\Presentation\Collection;

abstract class AbstractRepository
{
    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param Brownbag\Presentation\Model\ResourceModel\Presentation\Collection $collection
     */
    public function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param Brownbag\Presentation\Model\ResourceModel\Presentation\Collection $collection
     */
    public function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param Brownbag\Presentation\Model\ResourceModel\Presentation\Collection $collection
     */
    public function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @param Brownbag\Presentation\Model\ResourceModel\Presentation\Collection $collection
     * @param SearchResultInterface $searchResults
     * @return mixed
     */
    public function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection, SearchResultInterface $searchResults)
    {
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}