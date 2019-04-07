<?php

namespace Acidgreen\ContentManager\Setup;

use Psr\Log\LoggerInterface;
use Acidgreen\ContentManager\Helper\ContentHelper;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class UpgradeData
 * @package Acidgreen\ContentManager\Setup
 */
class UpgradeData implements UpgradeDataInterface
{
    /** @var LoggerInterface */
    public $logger;

    /** @var ContentHelper */
    private $contentHelper;

    /**
    * @var \Magento\UrlRewrite\Model\UrlRewriteFactory
    */
    protected $_urlRewriteFactory;

    /**
     * UpgradeData constructor.
     * @param LoggerInterface $logger
     * @param ContentHelper $contentHelper
     */
    public function __construct(
        LoggerInterface $logger,
        ContentHelper $contentHelper,
        \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory

    ) {
        $this->logger = $logger;
        $this->contentHelper = $contentHelper;
        $this->_urlRewriteFactory = $urlRewriteFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
    
        if (version_compare($context->getVersion(), '0.0.1', '<')) {
            $this->contentHelper->upsertPages([
                'customer-service-privacy-policy' => 'customer-service/privacy-policy'
            ]);
        }

        if (version_compare($context->getVersion(), '0.0.2', '<')) {
            $this->contentHelper->upsertPages([
                'customer-service-privacy-policy' => 'customer-service/privacy-policy',
                'customer-service-delivery' => 'customer-service/delivery',
                'customer-service-delivery-pick-up' => 'customer-service/delivery/pick-up',
                'customer-service-delivery-shipster-terms-condition' => 'customer-service/delivery/shipster-terms-condition',
                'customer-service-payment' => 'customer-service/payment',
                'customer-service-payment-afterpay' => 'customer-service/payment/afterpay',
                'customer-service-careers' => 'customer-service/careers',
                'customer-service-faqs' => 'customer-service/faqs',
                'customer-service-returns' => 'customer-service/returns',
                'customer-service-terms-of-use' => 'customer-service/terms-of-use'
            ]);
        }

        if (version_compare($context->getVersion(), '0.0.3', '<')) {
            $this->contentHelper->upsertBlocks([
                'footer-social-links' => 'footer-social-links',
                'copyright-links' => 'copyright-links'
            ]);
        }

        if (version_compare($context->getVersion(), '0.0.4', '<')) {
            $this->contentHelper->upsertBlocks([
                'top-promo-block' => 'top-promo-block'
            ]);
        }

        if (version_compare($context->getVersion(), '0.0.5', '<')) {
            $this->contentHelper->upsertPages([
                'customer-service' => 'customer-service/customer-service',
                'customer-service-delivery' => 'customer-service/delivery',
                'customer-service-delivery-pick-up' => 'customer-service/delivery/pick-up',
                'customer-service-delivery-shipster-terms-condition' => 'customer-service/delivery/shipster-terms-condition',
                'customer-service-returns' => 'customer-service/returns',
                'customer-service-careers' => 'customer-service/careers',
                'customer-service-payment' => 'customer-service/payment',
                'customer-service-payment-afterpay' => 'customer-service/payment/afterpay',
                'customer-service-faqs' => 'customer-service/faqs',
                'customer-service-terms-of-use' => 'customer-service/terms-of-use',
                'customer-service-privacy-policy' => 'customer-service/privacy-policy',

            ]);

            $urlRewriteModel = $this->_urlRewriteFactory->create();
            /* set current store id */
            $urlRewriteModel->setStoreId(1);
            /* this url is not created by system so set as 0 */
            $urlRewriteModel->setIsSystem(0);
            /* unique identifier - set random unique value to id path */
            $urlRewriteModel->setIdPath(rand(1, 100000));
            /* set actual url path to target path field */
            $urlRewriteModel->setTargetPath("customer-service/contact-us");
            /* set requested path which you want to create */
            $urlRewriteModel->setRequestPath("customer-service");
            /* set current store id */
            $urlRewriteModel->save();
        }

        if (version_compare($context->getVersion(), '0.0.6', '<')) {
            $this->contentHelper->upsertBlocks([
                'checkout-header-links' => 'checkout-header-links'
            ]);
        }
      
        $setup->endSetup();
    }
}