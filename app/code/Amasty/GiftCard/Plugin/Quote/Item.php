<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */


namespace Amasty\GiftCard\Plugin\Quote;

use Amasty\Base\Model\Serializer;
use Amasty\GiftCard\Helper\Data;

class Item
{
    private $price;
    /**
    * @var Data
    */
    protected $amHelper;
    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(Data $amHelper, Serializer $serializer)
    {
        $this->amHelper = $amHelper;
        $this->serializer = $serializer;
    }

    public function afterGetConvertedPrice(
        \Magento\Quote\Model\Quote\Item $item,
        $price
    ) {
        if ($this->price) {
            return $this->price;
        }
        $product = $item->getProduct();
        if ($product->getTypeId() == \Amasty\GiftCard\Model\Product\Type\GiftCard::TYPE_GIFTCARD_PRODUCT) {
            if (isset($item->getOptionsByCode()['info_buyRequest'])
                && isset($item->getOptionsByCode()['info_buyRequest']['value'])
            ) {
                $options = $this->serializer->unserialize($item->getOptionsByCode()['info_buyRequest']['value']);

                if (isset($options['am_giftcard_amount_custom']) && $options['am_giftcard_amount_custom']) {
                    $optionByCode = $item->getOptionByCode('am_giftcard_amount_custom')->getValue();
                    if ($optionByCode == false) {
                        $price = $item->getOptionByCode('am_giftcard_amount')->getValue();
                    } else {
                        $price = $optionByCode;
                    }
                }
                $feeType = $product->getAmGiftcardFeeType();
                /*missing gift card products options on checkout cart*/
                if ($feeType == null) {
                    $product->getResource()->load($product, $product->getId());
                    $feeType = $product->getAmGiftcardFeeType();
                }
                $feeValue = (float)$product->getAmGiftcardFeeValue();
                if ($feeType == \Amasty\GiftCard\Model\GiftCard::PRICE_TYPE_PERCENT) {
                    $price += $price * $feeValue / 100;
                } elseif ($feeType == \Amasty\GiftCard\Model\GiftCard::PRICE_TYPE_FIXED) {
                    $price = $price + $feeValue;
                }

                $this->price = $price;
            }
        }

        return $price;
    }
}
