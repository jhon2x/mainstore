<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */


namespace Amasty\GiftCard\Plugin\Quote;

use Magento\Catalog\Model\Product;

class AddToCart
{
    /**
     * @var \Amasty\GiftCard\Helper\Data
     */
    protected $amHelper;
    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    private $productRepository;

    public function __construct(
        \Amasty\GiftCard\Helper\Data $amHelper,
        \Magento\Catalog\Model\ProductRepository $productRepository
    ) {
        $this->amHelper = $amHelper;
        $this->productRepository = $productRepository;
    }

    /**
     * before add gift card check amount_custom to convert this to base currency
     *
     * @param \Magento\Checkout\Model\Cart $subject
     * @param int|Product $product
     * @param \Magento\Framework\DataObject|int|array|null $requestInfo
     *
     * @return array
     */
    public function beforeAddProduct(
        $subject,
        $product,
        $requestInfo = null
    ) {
        if ($product->getTypeId() === \Amasty\GiftCard\Model\Product\Type\GiftCard::TYPE_GIFTCARD_PRODUCT) {
            $this->checkForGiftCardType($product, $requestInfo);
            $quote = $subject->getQuote();
            $baseCurrencyCode = $quote->getBaseCurrencyCode();
            $quoteCurrencyCode = $quote->getQuoteCurrencyCode();
            if ($baseCurrencyCode !== $quoteCurrencyCode
                && isset($requestInfo['am_giftcard_amount_custom'])
                && $requestInfo['am_giftcard_amount_custom'] !== ""
            ) {
                $price = $this->amHelper->currencyConvert(
                    $requestInfo['am_giftcard_amount_custom'],
                    $quoteCurrencyCode,
                    $baseCurrencyCode
                );
                $requestInfo['am_giftcard_amount_custom'] = $price;
            }
        }

        return [$product, $requestInfo];
    }

    /**
     * @param int|Product $product
     * @param array|int|\Magento\Framework\DataObject|null $requestInfo
     */
    private function checkForGiftCardType($product, $requestInfo)
    {
        $giftType = isset($requestInfo['am_giftcard_type']) ? $requestInfo['am_giftcard_type'] : null;
        if ($giftType === null) {
            $product->setAmGiftCardType($product->getAmGiftcardType());

        } else {
            $product->setAmGiftCardType($giftType);
        }
        $this->productRepository->save($product);
    }
}
