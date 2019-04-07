<?php
/**
 * ACL. Can be queried for relations between roles and resources.
 *
 * Copyright © 2017 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

 namespace Acidgreen\Theme\Block\Html\Header;

 /**
  * @package Acidgreen\Unit3BlockArchitecture\Block
  */
 class MobileLogo extends \Magento\Framework\View\Element\Template
 {


 	private $customerSession;

 	public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->customerSession = $customerSession;
    }

    public function getCustomerName()
    {
    	$name = "Guest";

    	if($this->customerSession->isLoggedIn()) 
    	 	$name = $this->customerSession->getCustomer()->getFirstname();

    	return $name;
    }


    public function getCustomerLinks()
    {
    	$links = '<a href="'.$this->getUrl('customer/account/login/').'">Log In</a>';

    	if($this->customerSession->isLoggedIn()) {
    		$links = '<a href="'.$this->getUrl('customer/account/').'">My Account</a> <a href="'.$this->getUrl('customer/account/logout').'">Log Out</a>';
    	}

    	return $links;
    }

    public function getMobileLogo()
    {
    	$mobileLogo = '<img src="'.$this->getViewFileUrl('images/logo_mobile.png').'">';
    	if($this->customerSession->isLoggedIn()) {
    		$mobileLogo = '<span class="user_account_icon">';
    		$mobileLogo .= '<span class="initials">';
    		$mobileLogo .= $this->getCustomerInitials();
    		$mobileLogo .= '</span></span>';
    	}

    	return $mobileLogo;

    }

    private function getCustomerInitials()
    {
    	$fname_initial = substr($this->customerSession->getCustomer()->getFirstname(), 0, 1);
    	$lname_initial = substr($this->customerSession->getCustomer()->getLastname(), 0, 1);
    	return strtolower($fname_initial.$lname_initial);
    }

 }