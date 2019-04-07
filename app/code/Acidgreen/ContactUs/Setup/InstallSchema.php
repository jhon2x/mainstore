<?php
/**
 * ACL. Can be queried for relations between roles and resources.
 *
 * Copyright © 2017 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

 namespace Acidgreen\ContactUs\Setup;


use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

 /**
  * @package Acidgreen\Unit3BlockArchitecture\Block
  */
 class InstallSchema implements InstallSchemaInterface
{
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'catalog_product_entity'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('acidgreen_contact_form'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Entity ID'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false],
                'Customer or guest name'
            )
            ->addColumn(
                'email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                50,
                ['nullable' => false],
                'Email address'
            )
            ->addColumn(
                'order_number',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                20,
                ['nullable' => true],
                'Order Number'
            )
            ->addColumn(
                'issue',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                200,
                ['nullable' => false],
                'Issue'
            )
            ->addColumn(
                'comments',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => false],
                'Comments'
            )
             ->addColumn(
                'ip_address',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                32,
                ['nullable' => false],
                'IP address'
            )
             ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Created At'
            )
             ->setComment('Contact Form Data Table');
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}