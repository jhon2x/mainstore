<?php
/**
 * ACL. Can be queried for relations between roles and resources.
 *
 * Copyright © 2017 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

 namespace Acidgreen\Checkout\Setup;


use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

 /**
  * @package Acidgreen\Unit3BlockArchitecture\Block
  */
 class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
       
        if (version_compare($context->getVersion(), '0.0.2', '<')) {
            $setup->startSetup();
            $setup->getConnection()->addColumn($setup->getTable('sales_order'), 'delivery_instruction',   
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 100,
                    'nullable' => true,
                    'comment' => 'Delivery Instruction'
                ]);

            $setup->getConnection()->addColumn($setup->getTable('quote_address'), 'delivery_instruction',   
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 100,
                    'nullable' => true,
                    'comment' => 'Delivery Instruction'
                ]);

             $setup->getConnection()->addColumn($setup->getTable('quote_address'), 'newsletter_subscribe',   
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                    'nullable' => true,
                    'comment' => 'Subsribe to newsletter'
                ]);

            $setup->endSetup();
        }
    }
}