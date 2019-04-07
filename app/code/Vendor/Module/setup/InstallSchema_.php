<?php 

namespace 'Vendor\Module\Setup';

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;
class InstallSchema implements InstallSchemaInterface {

	/**
	 * Installs DB schema for a module
	 *
	 * @param SchemaSetupInterface $setup
	 * @param ModuleContextInterface $context
	 * @return void
	 */
	public function install(SchemaSetupInterface $setup, ModuleContextInterface $context){

		$installer = $setup->startSetup();

		$tableName = $installer->getTable('vendor_module_tb1');
		if(!$installer->tableExisits($tableName)){
			$table = $installer->getConnection()->newTable(
				$installer->getTable($tableName)
			)
			->addColumn(
				'table_id',
				Table::TYPE_INTEGER,
				null,
				[],
				'Column 1'
			)
			->setComment();

			$setup->getConnection()->createTable($table);
		}
		$setup->endSetup();
		
	}

}