<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Block\Adminhtml\Sales\Order\Create\GiftCard;

class Form extends \Magento\Sales\Block\Adminhtml\Order\Create\AbstractCreate
{

    protected function _construct()
    {
        parent::_construct();
        $this->setId('sales_order_create_coupons_form');
    }

    public function getCouponCode()
    {
        return $this->getParentBlock()->getQuote()->getCouponCode();
    }
}
