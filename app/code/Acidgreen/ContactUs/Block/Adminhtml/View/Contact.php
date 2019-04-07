<?php
/**
 * ACL. Can be queried for relations between roles and resources.
 *
 * Copyright © 2017 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

 namespace Acidgreen\ContactUs\Block\Adminhtml\View;

 use Magento\Backend\Block\Widget\Container;
 /**
  * @package Acidgreen\ContactUs\Block\Adminhtml\View
  */
 class Contact extends Container
 {
 	/**
     * Registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_registry;
    protected $_contact = null;
    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
         \Magento\Backend\Block\Widget\Context  $context,
        \Magento\Framework\Registry $registry,
        \Acidgreen\ContactUs\Model\Contact $contact,
    	array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_registry = $registry;
        $this->_contact = $contact;
        $this->addButton(
                'back',
                [
                'label' => __('Back'),
                'onclick' => 'setLocation(\'' . $this->getBackUrl() . '\')',
                'class' => 'back'
                ],
                -1
            );
    }

    public function getContactInquiry()
    {
    	
    	$this->_contact->load($this->_registry->registry('id'));
        if ($this->_contact->getId()) {
            return $this->_contact;
        }
    }
    private function getBackUrl(){
        return $this->getUrl('contact_form');
    }


 }