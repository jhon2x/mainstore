<?php
/**
 * Copyright © 2017 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Acidgreen\ContactUs\Model\ResourceModel;

/**
 * Class Game
 * @package Unit6\ComputerGames\Model\ResourceModel
 */
class Contact extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init('acidgreen_contact_form', 'id');
    }
}
