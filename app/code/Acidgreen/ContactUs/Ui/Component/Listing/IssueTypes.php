<?php
/**
 * Copyright © 2017 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Acidgreen\ContactUs\Ui\Component\Listing;

class IssueTypes implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {

        $this->options = array(
            array(
                'label' => __('Order'),
                'value' => 'Order'
            ),
            array(
                'label' => __('Shipping'),
                'value' => 'Shipping'
            ),
            array(
                'label' => __('Check out'),
                'value' => 'Check out'
            ),
            array(
                'label' => __('Returns'),
                'value' => 'Returns'
            ),
            array(
                'label' => __('Promo code'),
                'value' => 'Promo code'
            ),
            array(
                'label' => __('Restocking'),
                'value' => 'Restocking'
            ),
            array(
                'label' => __('Business & collaboration'),
                'value' => 'Business & collaboration'
            ),
            array(
                'label' => __('Careers & modelling'),
                'value' => 'Careers & modelling'
            ),
            array(
                'label' => __('Feedback'),
                'value' => 'Feedback'
            ),
            array(
                'label' => __('App feedback'),
                'value' => 'App feedback'
            )
        );

        return $this->options;
    }
}
