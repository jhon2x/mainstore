<?php

namespace Acidgreen\Checkout\Plugin\Checkout;

class SaveAddressInformation
{
    private $_logger;
    protected $quoteRepository;
    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
         \Psr\Log\LoggerInterface $logger
    ) {
        $this->quoteRepository = $quoteRepository;
         $this->_logger = $logger;
    }
    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        $shippingAddress = $addressInformation->getShippingAddress();
        $shippingAddressExtensionAttributes = $shippingAddress->getExtensionAttributes();
        if ($shippingAddressExtensionAttributes) {
            $customField = $shippingAddressExtensionAttributes->getDeliveryInstruction();
            $customFieldNewsletter = $shippingAddressExtensionAttributes->getNewsletterSubscribe();
            $shippingAddress->setDeliveryInstruction($customField);
            $shippingAddress->setNewsletterSubscribe($customFieldNewsletter);
        }

         $billingAddress = $addressInformation->getBillingAddress();
        $billingAddressExtensionAttributes = $billingAddress->getExtensionAttributes();
        if ($billingAddressExtensionAttributes) {
            $customField = $billingAddressExtensionAttributes->getDeliveryInstruction();
            $customFieldNewsletter = $billingAddressExtensionAttributes->getNewsletterSubscribe();
            $billingAddress->setDeliveryInstruction($customField);
            $billingAddress->setNewsletterSubscribe($customFieldNewsletter);
        }
          $this->_logger->info($customField.'************INFO*********', []);
          $this->_logger->info($customFieldNewsletter.'************INFO*********', []);
          $addressInformation->setShippingAddress($shippingAddress);
          $addressInformation->setBillingAddress($billingAddress);
          return [$cartId, $addressInformation];
    }
}