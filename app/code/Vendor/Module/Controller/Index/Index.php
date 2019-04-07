<?php 

namespace Vendor\Module\Controller\Index;

use Vendor\Module\Controller\Main;

class Index extends Main {

	public function execute(){
		
		// $page = $this->_resultPageFactory->create();
		// return $page;

		$redirect = $this->_redirect->setPath('*/*/');
		return $redirect;
	}
}