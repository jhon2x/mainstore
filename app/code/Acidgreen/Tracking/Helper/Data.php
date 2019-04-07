<?php
namespace Acidgreen\Tracking\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    protected $storeManager;
    protected $objectManager;

    const XML_PATH = 'agtracking/';

    public function __construct(Context $context,
                                ObjectManagerInterface $objectManager,
                                StoreManagerInterface $storeManager
    ) {
        $this->objectManager = $objectManager;
        $this->storeManager  = $storeManager;
        parent::__construct($context);
    }


    /***
     * Get confirm value from Backend
     *
     * @param string $code
     * @param integer $storeId
     * @return string
     */
    public function getConfig($code, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH.$code, ScopeInterface::SCOPE_STORE, $storeId
        );
    }


}