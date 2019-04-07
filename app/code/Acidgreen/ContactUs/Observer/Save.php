<?php
/**
 * ACL. Can be queried for relations between roles and resources.
 *
 * Copyright © 2018 Magento. All rights reserved.
 * See Copying.txt for license details.
 */
 
 namespace Acidgreen\ContactUs\Observer;

 /**
  * Class Log
  * @package Acidgreen\ContactUs\Observer
  */



 class Save implements \Magento\Framework\Event\ObserverInterface
 {
 	/**
 	 * @var \Psr\Log\LoggerInterface
 	 * @var \Magento\Framework\App\RequestInterface
 	 * @var Acidgreen\ContactUs\Model\Contact
 	 */
 	 private $_logger;
 	 protected $_request;
 	 protected $_contact = null;
 	 protected $_date;
 	 protected $_ip;

 	
 	 /**
 	  * Log constructor.
 	  * @param \Psr\Log\LoggerInterface $logger
 	  * @param \Magento\Framework\App\RequestInterface $request
 	  */
 	  public function __construct(
 	      \Psr\Log\LoggerInterface $logger,
 	  	  \Magento\Framework\App\RequestInterface $request,
 	  	  \Acidgreen\ContactUs\Model\Contact $contact,
 	  	  \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date,
 	  	  \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $ip
 	  	  )
 	  {
 	      $this->_logger = $logger;
 	      $this->_request = $request;
 	      $this->_contact = $contact;
 	      $this->_date = $date;
 	      $this->_ip = $ip;
 	  }

 	  /*
 	   * @param \Magento\Framework\Event\Observer $observer
 	   */
 	   public function execute(\Magento\Framework\Event\Observer $observer) {

 	   		$contact_form_data = array(
 	   			'name' => $this->_request->getPost('name'),
 	   			'email' => $this->_request->getPost('email'),
 	   			'order_number' => $this->_request->getPost('order-number'),
 	   			'issue' => $this->_request->getPost('issue'),
 	   			'comments' => $this->_request->getPost('comment'),
 	   			'ip_address' => $this->_ip->getRemoteAddress()
 	   		);
            $this->_contact->setData($contact_form_data)->save();
            $savedContactId = $this->_contact->getId();

            if($savedContactId)
            	$this->_logger->info("Saved contact form data: ".$savedContactId, []);
            else
            	$this->_logger->info("Error saving contact form data!", []);
 	   }
 }