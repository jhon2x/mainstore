<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Acidgreen\Checkout\Model;

use Magento\Checkout\Model\GuestPaymentInformationManagement as GuestPaymentInformationManagementModel;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class GuestPaymentInformationManagement extends GuestPaymentInformationManagementModel
{
	 /**
     * {@inheritDoc}
     */
    public function savePaymentInformationAndPlaceOrder(
        $cartId,
        $email,
        \Magento\Quote\Api\Data\PaymentInterface $paymentMethod,
        \Magento\Quote\Api\Data\AddressInterface $billingAddress = null
    ) {
        $salesConnection = $this->connectionPull->getConnection('sales');
        $checkoutConnection = $this->connectionPull->getConnection('checkout');
        $salesConnection->beginTransaction();
        $checkoutConnection->beginTransaction();

        try {
            $this->savePaymentInformation($cartId, $email, $paymentMethod, $billingAddress);
            $extAttributes = $billingAddress->getExtensionAttributes();
            $this->getLogger()->info($extAttributes->getDeliveryInstruction().'!!!!!!!!INFO!!!!!!!!!'.json_encode($billingAddress->getData()), []);
            try {
                $orderId = $this->cartManagement->placeOrder($cartId);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                throw new CouldNotSaveException(
                    __($e->getMessage()),
                    $e
                );
            } catch (\Exception $e) {
                $this->getLogger()->critical($e);
                throw new CouldNotSaveException(
                    __('An error occurred on the server. Please try to place the order again.'),
                    $e
                );
            }
            $salesConnection->commit();
            $checkoutConnection->commit();
        } catch (\Exception $e) {
            $salesConnection->rollBack();
            $checkoutConnection->rollBack();
            throw $e;
        }
        
        return $orderId;
    }
}