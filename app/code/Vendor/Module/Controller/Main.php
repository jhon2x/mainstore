<?php 

namespace Vendor\Module\Controller;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory as PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\Result\ForwardFactory;

Abstract class Main extends Action {

	protected $_resultPageFactory;
	protected $_jsonFactory;
	protected $_redirect;
	protected $_rawFactory;
	protected $_forwardFactory;

	public function __construct(
		Context $context,
		PageFactory $_resultPageFactory,
		JsonFactory $_jsonFactory,
		Redirect $_redirect,
		RawFactory $_rawFactory,
		ForwardFactory $_forwardFactory
	){	
		$this->_resultPageFactory = $_resultPageFactory;
		$this->_jsonFactory;
		$this->_redirect;
		$this->_rawFactory;
		$this->_forwardFactory;
		return parent::__construct($context);
	}
}