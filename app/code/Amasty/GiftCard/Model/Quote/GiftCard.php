<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Model\Quote;

use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Quote\Model\Quote;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Store\Model\StoreManagerInterface;

class GiftCard extends AbstractTotal
{
    /**
     * @var \Amasty\GiftCard\Model\AccountFactory
     */
    protected $accountModel;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Account
     */
    protected $accountResourceModel;
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $priceCurrency;
    /**
     * @var \Amasty\GiftCard\Helper\Data
     */
    protected $dataHelper;

    protected $giftCardLabel = [];
    protected $giftCardAmount;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var \Amasty\GiftCard\Model\ResourceModel\Quote\CollectionFactory
     */
    private $giftQuoteCollectionFactory;

    public function __construct(
        \Amasty\GiftCard\Model\AccountFactory $accountModel,
        StoreManagerInterface $storeManager,
        \Amasty\GiftCard\Model\ResourceModel\Account $accountResourceModel,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Amasty\GiftCard\Model\ResourceModel\Quote\CollectionFactory $giftQuoteCollectionFactory,
        \Amasty\GiftCard\Helper\Data $dataHelper,
        \Magento\Framework\Registry $registry
    ) {
        $this->accountModel = $accountModel;
        $this->storeManager = $storeManager;
        $this->accountResourceModel = $accountResourceModel;
        $this->priceCurrency = $priceCurrency;
        $this->dataHelper = $dataHelper;
        $this->registry = $registry;
        $this->giftQuoteCollectionFactory = $giftQuoteCollectionFactory;
    }

    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);
        if (!$this->dataHelper->isEnableGiftFormInCart($quote)) {
            $this->dataHelper->removeAllCards($quote);
        }

        $rate = $quote->getBaseToQuoteRate();

        $quoteId = $quote->getId();
        $giftCardsWithAccount = $this->getGiftCardsWithAccount($quoteId);

        $giftAmount = 0;
        $baseGiftAmount = 0;

        $amount = $total->getSubtotal() + $total->getDiscountAmount();
        $baseAmount = $total->getBaseSubtotal() +  $total->getBaseDiscountAmount();

        list($baseAdditionalAmount, $additionalAmount) = $this->getAdditionalAmount($total);

        if ($baseAmount > 0) {
            foreach ($giftCardsWithAccount as $giftCard) {
                $currentValue = $giftCard->getCurrentValue();
                $currentValueRate = $currentValue * $rate;
                $giftAmount += $currentValueRate;
                $baseGiftAmount += $currentValue;

                if ($amount - $giftAmount < 0) {
                    $giftAmount = $amount;
                    $baseGiftAmount = $baseAmount;

                    //apply for tax and shipping
                    $delta = $currentValueRate - $amount;
                    $baseDelta = $currentValue - $baseAmount;
                    $giftAmount += ($additionalAmount > $delta) ? $delta : $additionalAmount;
                    $baseGiftAmount += ($baseAdditionalAmount > $baseDelta) ? $baseDelta : $baseAdditionalAmount;

                    $giftAmount = min($giftAmount, $total->getGrandTotal());
                    $baseGiftAmount = min($baseGiftAmount, $total->getBaseGrandTotal());
                    $giftCard->setGiftAmount($giftAmount);
                    $giftCard->setBaseGiftAmount($baseGiftAmount);
                    $giftCard->save();
                    break;
                } elseif ($amount - $giftAmount > 0 && $currentValue && $currentValueRate) {
                    $giftCard->setGiftAmount($currentValueRate);
                    $giftCard->setBaseGiftAmount($currentValue);
                    $giftCard->save();
                }
            }

            $total->setTotalAmount($this->getCode(), -$giftAmount);
            $total->setBaseTotalAmount($this->getCode(), -$baseGiftAmount);

            $total->setAmastyGift($giftAmount);
            $total->setBaseAmastyGift($baseGiftAmount);

            $quote->setAmastyGift($giftAmount);
            $quote->setBaseAmastyGift($baseGiftAmount);

            $total->setGrandTotal($total->getGrandTotal() - $giftAmount);
            $total->setBaseGrandTotal($total->getBaseGrandTotal() - $baseGiftAmount);

            $this->giftCardAmount = $giftAmount;
        }

        return $this;
    }

    /**
     * Returns shipping and/or tax amounts, depends on config options.
     * @param Total $total
     * @return array
     */
    private function getAdditionalAmount(Total $total)
    {
        $baseAmount = 0;
        $amount = 0;
        if ($this->dataHelper->isAllowedToPaidForShipping()) {
            $baseAmount = $total->getData('base_shipping_amount');
            $amount = $total->getData('shipping_amount');
        }
        if ($this->dataHelper->isAllowedToPaidForTax()) {
            $baseAmount += $total->getData('base_tax_amount');
            $amount += $total->getData('tax_amount');
        }

        return [$baseAmount, $amount];
    }

    public function fetch(Quote $quote, Total $total)
    {
        if (!$this->registry->registry('amasty_giftcard')) {
            $this->registry->register('amasty_giftcard', true);
            $quoteId = $quote->getId();
            $giftCardsWithAccount = $this->getGiftCardsWithAccount($quoteId);

            $discount = 0;
            foreach ($giftCardsWithAccount as $giftCard) {
                $giftAmount= $giftCard->getGiftAmount();
                if ($giftAmount != 0) {
                    $discount += $giftAmount;
                    if (!in_array($giftCard->getCode(), $this->giftCardLabel)) {
                        $this->giftCardLabel[] = $giftCard->getCode();
                    }
                }
            }
            if ($discount) {
                return [
                    'code' => $this->getCode(),
                    'title' => __(implode(', ', $this->giftCardLabel)),
                    'value' => -$discount
                ];
            }
        }
    }

    /**
     * @param $quoteId
     * @return \Amasty\GiftCard\Model\ResourceModel\Quote\Collection
     */
    private function getGiftCardsWithAccount($quoteId)
    {
        return $this->giftQuoteCollectionFactory->create()->getGiftCardsWithAccount($quoteId);
    }
}
