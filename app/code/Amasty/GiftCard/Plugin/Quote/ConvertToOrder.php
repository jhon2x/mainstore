<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */


namespace Amasty\GiftCard\Plugin\Quote;

use Closure;

class ConvertToOrder
{
    /**
     * @var \Amasty\GiftCard\Helper\Data
     */
    protected $amHelper;
    /**
     * @var \Amasty\Base\Model\Serializer
     */
    private $serializer;

    public function __construct(
        \Amasty\GiftCard\Helper\Data $amHelper,
        \Amasty\Base\Model\Serializer $serializer
    ) {
        $this->amHelper = $amHelper;
        $this->serializer = $serializer;
    }

    public function aroundConvert(
        \Magento\Quote\Model\Quote\Item\ToOrderItem $subject,
        Closure $proceed,
        \Magento\Quote\Model\Quote\Item\AbstractItem $item,
        $additional = []
    ) {
        /** @var $orderItem \Magento\Sales\Model\Order\Item */
        $orderItem = $proceed($item, $additional);

        $keys = [
            'am_giftcard_amount',
            'am_giftcard_amount_custom',
            'am_giftcard_image',
            'am_giftcard_type',
            'am_giftcard_sender_name',
            'am_giftcard_sender_email',
            'am_giftcard_recipient_name',
            'am_giftcard_recipient_email',
            'am_giftcard_date_delivery',
            'am_giftcard_message'
        ];
        $productOptions = $orderItem->getProductOptions();
        $customOptions = [];

        if (is_array($item->getOptions())) {
            foreach ($item->getOptions() as $key => $itemOption) {
                if ($itemOption->getCode() == 'info_buyRequest' && $options = $itemOption->getValue()) {
                    $customOptions = $this->serializer->unserialize($options);
                }
            }
        }
        $product = $item->getProduct()->load($item->getProduct()->getId());
        foreach ($keys as $key) {
            if (array_key_exists($key, $customOptions)) {
                $productOptions[$key] = $customOptions[$key];
            }
        }

        $productOptions['am_giftcard_lifetime'] = $this->amHelper->getValueOrConfig(
            $product->getAmGiftcardLifetime(),
            \Amasty\GiftCard\Model\GiftCard::XML_PATH_LIFETIME,
            $orderItem->getStore()
        );

        $productOptions['am_giftcard_email_template'] = $this->amHelper->getValueOrConfig(
            $product->getAmEmailTemplate(),
            \Amasty\GiftCard\Model\GiftCard::XML_PATH_EMAIL_TEMPLATE,
            $orderItem->getStore()
        );

        $productOptions["am_giftcard_code_set"] = $item->getProduct()->getAmGiftcardCodeSet();

        $orderItem->setProductOptions($productOptions);

        return $orderItem;
    }
}

