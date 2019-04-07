<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */


namespace Amasty\GiftCard\Plugin\Quote;

use Amasty\GiftCard\Model\Product\Type\GiftCard;

class UpdateItem
{

    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    private $productRepository;

    public function __construct(\Magento\Catalog\Model\ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * save type of gift card
     *
     * @param \Magento\Quote\Model\Quote $subject
     * @param callable $proceed
     * @param int $itemId
     * @param \Magento\Framework\DataObject $buyRequest
     * @param null|array|\Magento\Framework\DataObject $params
     * @return \Magento\Quote\Model\Quote\Item
     */
    public function aroundUpdateItem($subject, callable $proceed, $itemId, $buyRequest, $params = null)
    {
        $item = $subject->getItemById($itemId);
        $productId = $item->getProduct()->getId();
        $product = $this->productRepository->getById($productId);
        if ($product->getTypeId() === GiftCard::TYPE_GIFTCARD_PRODUCT) {
            $product->setAmGiftCardType($buyRequest->getAmGiftcardType());
            $this->productRepository->save($product);
        }

        return $proceed($itemId, $buyRequest, $params = null);
    }
}
