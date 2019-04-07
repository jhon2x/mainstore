<?php
/**
 * Copyright © 2017 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Acidgreen\ContactUs\Model;

/**
 * Class Game
 * @package Unit6\ComputerGames\Model
 */
class Contact extends \Magento\Framework\Model\AbstractExtensibleModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Acidgreen\ContactUs\Model\ResourceModel\Contact');
    }

    /**
     * @return array
     */
    public function getCustomAttributesCodes()
    {
        return array('id', 'name', 'email', 'order_number', 'issue', 'comments', 'ip_address' ,'created_at');
    }
}