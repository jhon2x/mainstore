<?php
/**
 *  Copyright © 2017 Magento. All rights reserved.
 *  See COPYING.txt for license details.
 */
namespace Acidgreen\ContactUs\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\RedirectFactory;
use Acidgreen\ContactUs\Model\Contact;

/**
 * Class Delete
 * @package Acidgreen\ContactUs\Controller\Adminhtml\Index\Delete
 */
class Delete extends Action
{
    /**
     * @var null|Game
     */
    protected $contact = null;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * Edit constructor.
     */
    public function __construct(Action\Context $context, Contact $contact, RedirectFactory $redirectFactory)
    {
        $this->contact = $contact;
        $this->resultRedirectFactory = $redirectFactory;
        parent::__construct($context);
    }

    /**
     * Save action
     */
    public function execute()
    {
        $entityId = $this->getRequest()->getParam('id');

        $this->contact->load($entityId);
        if ($this->contact->getId()) {
            $this->contact->delete();
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/index');
        return $resultRedirect;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Acidgreen_ContactUs::grid');
    }
     /**
     * Link must be generated by server side
     * It's only for education purpose!
     *
     * @return bool
     */
    public function _processUrlKeys()
    {
        return true;
    }
}