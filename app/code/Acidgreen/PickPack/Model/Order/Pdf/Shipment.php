<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Acidgreen\PickPack\Model\Order\Pdf;

use Magento\Sales\Model\Order\Pdf\Shipment as MagentoShipment;

/**
 * Sales Order Shipment PDF model
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Shipment extends MagentoShipment
{
	/**
     * Draw table header for product items
     *
     * @param  \Zend_Pdf_Page $page
     * @return void
     */
    protected function _drawHeader(\Zend_Pdf_Page $page)
    {
        /* Add table head */
        $this->_setFontRegular($page, 10);
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
        $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 570, $this->y - 15);
        $this->y -= 10;
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0, 0, 0));

        //columns headers
        $lines[0][] = ['text' => __('Products'), 'feed' => 100];

        $lines[0][] = ['text' => __('Section'), 'feed' => 285];

        $lines[0][] = ['text' => __('Aisle'), 'feed' => 335];

        $lines[0][] = ['text' => __('Shelf'), 'feed' => 385];

        $lines[0][] = ['text' => __('Box'), 'feed' => 435];

        $lines[0][] = ['text' => __('SKU'), 'feed' => 565, 'align' => 'right'];

        $lineBlock = ['lines' => $lines, 'height' => 10];

        $this->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $this->y -= 20;
    }

}