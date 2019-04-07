<?php 

namespace Brownbag\Presentation\Model; 

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Exception\ValidatorException;
use Magento\Framework\Api\SearchCriteriaInterface;

// Api
use Brownbag\Presentation\Api\PresentationRepositoryInterface;
use Brownbag\Presentation\Api\Data\PresentationInterface as ItemInterface;
use Brownbag\Presentation\Api\Data\PresentationSearchResultInterfaceFactory;
// Model
use Brownbag\Presentation\Model\ResourceModel\Presentation as PresentationResource;
use Brownbag\Presentation\Model\ResourceModel\Presentation\Collection;
use Brownbag\Presentation\Model\ResourceModel\Presentation\CollectionFactory;

class PresentationRepository implements PresentationRepositoryInterface {

	protected $_instances;
	protected $_presentationResource;
	protected $_presentationFactory;
	protected $_presentationCollectionFactory;
	protected $_searchResultFactory; 

	public function __construct(
		PresentationResource $presentationResource,
		PresentationFactory	$logFactory,
		CollectionFactory $logCollectionFactory,
		PresentationSearchResultInterfaceFactory $searchResultFactory
	){
		$this->_presentationResource = $presentationResource;
		$this->_presentationFactory = $logFactory; 
		$this->_presentationCollectionFactory = $logCollectionFactory;
		$this->_searchResultFactory = $searchResultFactory;
	}

	public function save(ItemInterface $object){

		try {
			$this->_presentationResource->save($object);
		} catch (Exception $e) {
			throw new CouldNotSaveException(__(
			  	'Could not save the record: %1',
			  	$e->getMessage()
			));
		}

		return $object;
	}

	public function getById($id){

		if(!$this->_instances[$id]){
			$object = $this->_presentationFactory->create();
			$this->_presentationResource->load($object, $id);
			if(!$object->getId()){
				// throw new NoSuchEntityException(__('Data does not exist'));
				return false;
			} 
			$this->_instances[$id] = $object;
		}
		return $this->_instances[$id];
	}

	public function delete(ItemInterface $object){
		$id = $object->getId();
		try {
		    unset($this->_instances[$id]);
		    $this->_presentationResource->delete($object);
		} catch (ValidatorException $e) {
		    throw new CouldNotSaveException(__($e->getMessage()));
		} catch (\Exception $e) {
		    throw new StateException(
		        __('Unable to remove %1', $id)
		    );
		}
		unset($this->_instances[$id]);
		return true;
	}

	/**
	 * @param int $id
	 * @return void
	 */
	public function deleteById($id){
		return $this->delete($this->getById($id));
	}

	/**
	 * @param SearchCriteriaInterface $searchCriteria
	 * @return \Brownbag\Presentation\Api\Data\PresentationSearchResultInterface
	 */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->_presentationCollectionFactory->create();
 
        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);
 
        $collection->load();
 
        return $this->buildSearchResult($searchCriteria, $collection);
    }
 
    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
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
 
    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }
 
    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }
 
    private function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $searchResults = $this->_searchResultFactory->create();
 
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
 
        return $searchResults;
    }


}