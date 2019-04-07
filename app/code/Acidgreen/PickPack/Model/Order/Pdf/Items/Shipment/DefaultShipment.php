<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Acidgreen\PickPack\Model\Order\Pdf\Items\Shipment;

use Magento\Sales\Model\Order\Pdf\Items\Shipment\DefaultShipment as MagentoDefaultShipment;

/**
 * Sales Order Shipment Pdf default items renderer
 */
class DefaultShipment extends MagentoDefaultShipment
{

	private $_productLoader;
	 /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Tax\Helper\Data $taxData
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Filter\FilterManager $filterManager
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Tax\Helper\Data $taxData,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filter\FilterManager $filterManager,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        
        $this->_productLoader = $productFactory->create();
        parent::__construct(
            $context,
            $registry,
            $taxData,
            $filesystem,
            $filterManager,
            $string,
            $resource,
            $resourceCollection,
            $data
        );
    }

	/**
     * Draw item line
     *
     * @return void
     */
    public function draw()
    {
        $item = $this->getItem();
        $pdf = $this->getPdf();
        $page = $this->getPage();
        $lines = [];

        // draw Product name
        $lines[0] = [['text' => $this->string->split($item->getName(), 60, true, true), 'feed' => 100]];

        // draw QTY
        $lines[0][] = ['text' => $item->getQty() * 1, 'feed' => 35];

        // draw SKU
        $lines[0][] = [
            'text' => $this->string->split($this->getSku($item), 25),
            'feed' => 565,
            'align' => 'right',
        ];

        // Custom options
        $options = $this->getItemOptions();

        if ($options) {
            foreach ($options as $option) {
                // draw options label
                $lines[][] = [
                    'text' => $this->string->split($this->filterManager->stripTags($option['label']), 70, true, true),
                    'font' => 'italic',
                    'feed' => 110,
                ];

                // draw options value
                if ($option['value']) {
                    $printValue = isset(
                        $option['print_value']
                    ) ? $option['print_value'] : $this->filterManager->stripTags(
                        $option['value']
                    );
                    $values = explode(', ', $printValue);
                    foreach ($values as $value) {
                        $lines[][] = ['text' => $this->string->split($value, 50, true, true), 'feed' => 115];
                    }
                }
            }
        }
        
        $lines = $this->addWarehouseDetails($lines, $item); 
        

        $lineBlock = ['lines' => $lines, 'height' => 20];

        $page = $pdf->drawLineBlocks($page, [$lineBlock], ['table_header' => true]);
        $this->setPage($page);
    }

    /**
     * Add Warehouse Details from Product Attributes
     * @param $lines 
     * @param $item: Load product from $item->getProductId() since the getData attribute is not readily available within $item
     * @return $lines
     */
    private function addWarehouseDetails($lines, $item){
   
    	$id = $item->getProductid();
	    $product = $this->_productLoader->load($id);

	    $warehouse_section = $product->getData('warehouse_section');
        $lines[0][] = [
                    'text' => $this->string->split($warehouse_section, 30, true, true),
                    'feed' => 285,
                ];

        $warehouse_aisle = $product->getData('warehouse_aisle');
        $lines[0][] = [
                    'text' => $this->string->split($warehouse_aisle, 30, true, true),
                    'feed' => 335,
                ];

        $warehouse_shelf = $product->getData('warehouse_shelf');
        $lines[0][] = [
                    'text' => $this->string->split($warehouse_shelf, 30, true, true),
                    'feed' => 385,
                ];

        $warehouse_box = $product->getData('warehouse_box');
        $lines[0][] = [
                    'text' => $this->string->split($warehouse_box, 30, true, true),
                    'feed' => 435,
                ];


        return $lines;
    }
}
