<?php

namespace Acidgreen\Theme\Helper;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Catalog\Pricing\Price\RegularPrice;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /** @var StoreManagerInterface _storeManager */
    private $_storeManager;
    protected $_scopeConfig;

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;
    }

    /***
     * Get product custom price
     *
     * @param Product $_product
     * @return string
     */
    public function displayCustomPrice(Product $_product)
    {
        $currencySymbol = $this->_storeManager->getStore()->getCurrentCurrency()->getCurrencySymbol();

        $originalPrice = number_format((float)$_product->getPriceInfo()->getPrice(RegularPrice::PRICE_CODE)->getAmount()->getValue(),
            2, '.', ',');
        $finalPrice = number_format((float)$_product->getPriceInfo()->getPrice(FinalPrice::PRICE_CODE)->getAmount()->getValue(),
            2, '.', ',');

        $priceHtml = [
            "container_open" => "<div class=\"discount-label\">",
            "current_price" => "<span class=\"current-price\">" . $currencySymbol . $finalPrice . "</span>",
            "original_price" => "",
            "percentage" => "",
            "container_close" => "</div>"
        ];

        if ($originalPrice > $finalPrice) {
            $percentage = number_format(($originalPrice - $finalPrice) * 100 / $originalPrice, 0);
            $priceHtml["original_price"] = "<span class=\"original-price\">" . $currencySymbol . $originalPrice . "</span>";
            $priceHtml["percentage"] = "<span class=\"percentage-off\">" . $percentage . " % OFF</span>";
        }

        return implode("", $priceHtml);
    }

    /***
     * Check if product has special price
     *
     * @param Product $_product
     * @return bool
     */
    public function hasSpecialPrice(Product $_product)
    {
        $originalPrice = number_format((float)$_product->getPriceInfo()->getPrice(RegularPrice::PRICE_CODE)->getAmount()->getValue(),
            2, '.', ',');
        $finalPrice = number_format((float)$_product->getPriceInfo()->getPrice(FinalPrice::PRICE_CODE)->getAmount()->getValue(),
            2, '.', ',');

        if ($originalPrice > $finalPrice) {
            return true;
        }

        return false;
    }

    /***
     * Get store config
     *
     * @param string $path
     * @param int|null $storeId
     * @return mixed
     */
    public function getConfig(string $path, int $storeId = null)
    {
        return $this->_scopeConfig->getValue(
            $path, ScopeInterface::SCOPE_STORE, $storeId
        );
    }
}