<?php
namespace Acidgreen\Tracking\Block;

class Unidays extends \Magento\Framework\View\Element\Template
{


    protected $config;
    protected $_checkoutSession;
    protected $customer;
    protected $storeManager;
    protected $currencyFactory;
    protected $api;
    protected $logger;

    public function __construct(
        \Acidgreen\Tracking\Model\UnidaysTrackingApi $api,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Acidgreen\Tracking\Helper\Data $config,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\CurrencyFactory $currencyFactory,
        \Psr\Log\LoggerInterface $logger,
        array $data = []
    ){
        $this->config = $config;
        $this->api = $api;
        $this->storeManager = $storeManager;
        $this->_checkoutSession = $checkoutSession;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Get Tracking URL for Unidays
     *
     * @return string
     *
     */
    public function getTrackingUrl()
    {
        $order = $this->getOrder();

        $key = trim($this->getConfig("signed_key"));
        $this->writeLog("Start to generate tracking URL");

        /** Check for enable **/
        if(!$this->getConfig("enable")){
            $this->writeLog("Tracking extension is disabled");
            return null;
        }

        /** Check for signing key */
        if(!$key){
            $this->writeLog("Singing Key in config is empty");
            return null;
        }

        /** Check for order */
        if(!$order){
            $this->writeLog("Order is null");
            return null;
        }

        $code = $order->getCouponCode();
        $prefix = $this->getConfig("prefix");

        /** Check for coupon code */
        if(substr($code,0, strlen($prefix)) != $prefix ){
            $this->writeLog("Customer not use coupon code or coupon not valid");
            return null;
        }

        $country = $order->getBillingAddress()->getCountryId();
        $custConfigCode = "cust_id_".strtolower($country);
        $cust_id = trim($this->getConfig($custConfigCode));
        $this->writeLog("Cust Config Code:".$custConfigCode);

        /** Check customer key in config **/
        if(empty($cust_id)) {
            $this->writeLog("Customer key for $country is empty");
            return null;
        }

        $url = $this->getConfig("url");

        $this->api->setUp($cust_id,$key, $url);

        $cartDiscount = abs($order->getDiscountAmount());
        $shippingDiscount = abs($order->getShippingDiscountAmount());
        $discount = $cartDiscount + $shippingDiscount;
        $subtotal = $order->getSubtotalInclTax();
        $currencyCode = $order->getOrderCurrencyCode();

        $discountPercent = number_format(($discount/$subtotal)*100,2);

        $is_guest = $order->getCustomerId()? 0 : 1;

        return $this->api->createUrl(
            $order->getIncrementId(),
            null,
            $currencyCode,
            $order->getGrandTotal(),
            $cartDiscount,
            $code,
            $order->getTaxAmount(),
            $order->getShippingAmount(),
            $shippingDiscount,
            $subtotal,
            0,
            $discountPercent,
            $is_guest,
            'pixel');
    }

    /***
     * Get confirm value from Backend
     *
     * @param string $code
     * @param integer $storeId
     * @return string
     */
    public function getConfig($code,$storeId = null)
    {
        return $this->config->getConfig("unidays/".$code,$storeId);
    }


    /***
     * Write debug message
     *
     * @param $message
     */
    public function writeLog($message)
    {
        if($this->getConfig("debug")) {
            $this->logger->info($message);
        }
    }


    /**
     * Get Order
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        $order  = $this->_checkoutSession->getLastRealOrder();
        $order->getShippingAddress()->getRegion();
        return  $this->_checkoutSession->getLastRealOrder();
    }
}