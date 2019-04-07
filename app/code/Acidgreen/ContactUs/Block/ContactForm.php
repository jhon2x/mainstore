<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Acidgreen\ContactUs\Block;

use Magento\Contact\Block\ContactForm as MagentoContactForm;
use Magento\Framework\View\Element\Template;
/**
 * Main contact form block
 *
 * @api
 * @since 100.0.2
 */
class ContactForm extends MagentoContactForm
{
    /**
     * @param Template\Context $context
     * @param array $data
     */
    private $_issueTypes;

    public function __construct(
        Template\Context $context,
        \Acidgreen\ContactUs\Ui\Component\Listing\IssueTypes $issueTypes,
     array $data = [])
    {
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
        $this->_issueTypes = $issueTypes;
    }

    public function getIssueTypes(){
        return $this->_issueTypes->toOptionArray();
    }

    
}
