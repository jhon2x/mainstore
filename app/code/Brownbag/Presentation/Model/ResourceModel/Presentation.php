<?php 

namespace Brownbag\Presentation\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Brownbag\Presentation\Model\Presentation as Model;

class Presentation extends AbstractDb {

	const MAIN_TABLE = 'brownbag_presentation';

	/**
	 * Initialize resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
	    $this->_init(self::MAIN_TABLE, Model::ID);
	}
}	