<?php
/**
 * Copyright © 2017 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Acidgreen\ContactUs\Model\ResourceModel\Contact;
use Magento\Framework\Api\Search\SearchResultInterface;

/**
 * Class Collection
 * @package Unit6\ComputerGames\Model\ResourceModel\Game
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
    implements SearchResultInterface
{
      /**
     * @var string
     */
    protected $_idFieldName = 'id';
    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init(
            'Acidgreen\ContactUs\Model\Contact',
            'Acidgreen\ContactUs\Model\ResourceModel\Contact'
        );
    }

    /**
     * @return AggregationInterface
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * @param AggregationInterface $aggregations
     * @return $this
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }

    /**
     * Get search criteria.
     *
     * @return \Magento\Framework\Api\SearchCriteriaInterface|null
     */
    public function getSearchCriteria()
    {
        return $this->searchCriteria;
    }

    /**
     * Set search criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {
        $this->searchCriteria = $searchCriteria;
        return $this;
    }

    /**
     * Get total count.
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * Set total count.
     * @param int $totalCount
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setTotalCount($totalCount)
    {
        $this->setSize($totalCount);
        return $this;
    }

    /**
     * Set items list.
     * @param \Magento\Framework\Api\ExtensibleDataInterface[] $items
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setItems(array $items = null)
    {
        return $this;
    }

}