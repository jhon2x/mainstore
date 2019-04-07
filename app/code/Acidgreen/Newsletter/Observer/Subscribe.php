<?php
/**
 * ACL. Can be queried for relations between roles and resources.
 *
 * Copyright © 2018 Magento. All rights reserved.
 * See Copying.txt for license details.
 */
 
 namespace Acidgreen\Newsletter\Observer;

 /**
  * Class Log
  * @package Acidgreen\Newsletter\Observer
  */



 class Subscribe implements \Magento\Framework\Event\ObserverInterface
 {

 	const MAILCHIMP_APIKEY = '5a293b5940df7ed89d2a9250cafc61c7-us19';
 	const MAILCHIMP_APIURL = '.api.mailchimp.com/3.0';
 	const MAILCHIMP_API_SUCCESS_CODE = 200;
 	/**
 	 * @var \Psr\Log\LoggerInterface
 	 * @var \Magento\Framework\App\RequestInterface
 	 * @var \Acidgreen\Newsletter\Block\Subscribe
 	 * @var \Magento\Framework\HTTP\Adapter\CurlFactory
 	 */
 	 private $_logger;
 	 protected $_request;
 	 private $_list;
 	 protected $curlFactory;
 	 

 	
 	 /**
 	  * Log constructor.
 	  * @param \Psr\Log\LoggerInterface $logger
 	  * @param \Magento\Framework\App\RequestInterface $request
 	  * @param \Acidgreen\Newsletter\Block\Subscribe
 	  * @param \Magento\Framework\HTTP\Adapter\CurlFactory
 	  */
 	  public function __construct(
 	      \Psr\Log\LoggerInterface $logger,
 	  	  \Magento\Framework\App\RequestInterface $request,
 	  	  \Acidgreen\Newsletter\Block\Subscribe $subsribeList,
 	  	  \Magento\Framework\HTTP\Adapter\CurlFactory $curlFactory
 	  	  )
 	  {
 	      $this->_logger = $logger;
 	      $this->_request = $request;
 	      $this->_list = $subsribeList->getGender();
 	      $this->curlFactory = $curlFactory;
 	  }

 	  /**
 	   * @param \Magento\Framework\Event\Observer $observer
 	   */
 	   public function execute(\Magento\Framework\Event\Observer $observer) {

	 	   	$newsletter_preference = $this->_request->getPost('newsletter_preference');
		    $email = $this->_request->getPost('email');

	 	   	$subscribe_status = $this->subscribeToList($newsletter_preference,$email);
	        
	        // log message based on response code
	        if ($subscribe_status == self::MAILCHIMP_API_SUCCESS_CODE) {
	            $this->_logger->info("Email ".$email." successfully added to list.", []);
	        } else {
	             $this->_logger->info("Error subsribing to mailchim:".$log_message, []);
	        }
 	   }

 	   /**
 	   * @param newsletter_preference
 	   */
 	   private function getListID($newsletter_preference){
 	   		$key = array_search($newsletter_preference, array_column($this->_list, 'value'));
 	   		return $this->_list[$key]['listId'];
 	   }

 	   /**
 	   * @param newsletter_preference
 	   * @param email
 	   */
 	   private function subscribeToList($newsletter_preference, $email){
 	   		// MailChimp API credentials
	        $apiKey = self::MAILCHIMP_APIKEY;
	        $listID = $this->getListID($newsletter_preference);
	        
	        // MailChimp API URL
	        $memberID = md5(strtolower($email));
	        $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
	        $url = 'https://' . $dataCenter . self::MAILCHIMP_APIURL.'/lists/' . $listID . '/members/' . $memberID;
	        
		    // subsriber information
	        $json = json_encode([
	            'email_address' => $email,
	            'status'        => 'subscribed',
	        ]);

	        $headers = ["Content-Type" => "application/json"];
		    /* Create curl factory */
		    $httpAdapter = $this->curlFactory->create();
		    $httpAdapter->addOption(CURLOPT_USERPWD,'user:' . $apiKey);
		    $httpAdapter->write(\Zend_Http_Client::PUT, $url, '1.1', $headers,$json);
		    $result = $httpAdapter->read();
		    $httpCode = $httpAdapter->getInfo(CURLINFO_HTTP_CODE);
		    $result = \Zend_Http_Response::extractBody($result);
		    return $httpCode; 
		}
 }