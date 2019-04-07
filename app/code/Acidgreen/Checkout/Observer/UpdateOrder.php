<?php
/**
 * ACL. Can be queried for relations between roles and resources.
 *
 * Copyright © 2018 Magento. All rights reserved.
 * See Copying.txt for license details.
 */
 
 namespace Acidgreen\Checkout\Observer;

 /**
  * Class Log
  * @package Acidgreen\Newsletter\Observer
  */
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Checkout\Model\Session as CheckoutSession;


 class UpdateOrder implements \Magento\Framework\Event\ObserverInterface
 {

 	/**
 	 * @var \Psr\Log\LoggerInterface
 	 * @var \Magento\Framework\App\RequestInterface
 	 */
 	 private $_logger;
 	 protected $_order;
 	 protected $_quoteFactory;
 	 protected $_subscriberFactory;
 	 
 	
 	 /**
 	  * Log constructor.
 	  * @param \Psr\Log\LoggerInterface $logger
 	  * @param \Magento\Framework\App\RequestInterface $request
 	  * @param \Acidgreen\Newsletter\Block\Subscribe
 	  * @param \Magento\Framework\HTTP\Adapter\CurlFactory
 	  */
 	  public function __construct(
 	      \Psr\Log\LoggerInterface $logger,
 	  	   \Magento\Sales\Api\Data\OrderInterface $order,
 	  	   \Magento\Quote\Model\QuoteFactory $quoteFactory,
 	  	   \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
 	  	  )
 	  {
 	      $this->_logger = $logger;
 	      $this->_order = $order;    
 	      $this->_quoteFactory = $quoteFactory;
 	      $this->_subscriberFactory = $subscriberFactory;
 	  }
 	  public function execute(\Magento\Framework\Event\Observer $observer)
		{
		    $orderids = $observer->getEvent()->getOrderIds();
 	   		 foreach($orderids as $orderid){
	            $order = $this->_order->load($orderid);
	            try {

	            	$quote = $this->_quoteFactory->create()->loadByIdWithoutStore($order->getQuoteId());
	            	$quoteShipping = $quote->getShippingAddress();
	            	$order->setDeliveryInstruction($quoteShipping->getDeliveryInstruction());

	            	if($quoteShipping->getNewsletterSubscribe() == 1)
                		$this->getSubscriberFactory()->subscribe($order->getCustomerEmail());

                	$order->save();

	            } catch (\Exception $e) {
	            }
	        }
		   
		    
		}
	 /**
     * @return \Magento\Newsletter\Model\Subscriber
     */
    private function getSubscriberFactory()
    {
        return $this->_subscriberFactory->create();
    }

 }