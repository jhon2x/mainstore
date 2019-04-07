<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Model\ResourceModel\Image;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Amasty\GiftCard\Model\Image', 'Amasty\GiftCard\Model\ResourceModel\Image');
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }

    public function toOptionArray()
    {
        return $this->_toOptionArray('image_id', 'title');
    }
}
