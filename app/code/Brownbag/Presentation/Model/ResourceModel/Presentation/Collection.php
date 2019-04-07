<?php 

namespace Brownbag\Presentation\Model\ResourceModel\Presentation;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

use Brownbag\Presentation\Model\Presentation as Model;
use Brownbag\Presentation\Model\ResourceModel\Presentation as ResourceModel;

class Collection extends AbstractCollection {

	/**
	 * @var string
	 */
	protected $_idFieldName = 'repository_log_id';

	/**
	 * Initialization here
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init(Model::class, ResourceModel::class);
	}
}