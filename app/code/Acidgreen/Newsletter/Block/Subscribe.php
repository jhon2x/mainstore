<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Newsletter subscribe block
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Acidgreen\Newsletter\Block;

use Magento\Newsletter\Block\Subscribe as MagentoSubscribe;

/**
 * @api
 * @since 100.0.2
 */
class Subscribe extends MagentoSubscribe
{
    public function getGender(){
        return array(
            array(
                'label' => __('Join Womens'),
                'value' => 'Women',
                'listId' => 'd4d8e9a09b'
            ),
            array(
                'label' => __('Join Mens'),
                'value' => 'Men',
                'listId' => '6b72c86c5f'
            ),
        );
    }
}
