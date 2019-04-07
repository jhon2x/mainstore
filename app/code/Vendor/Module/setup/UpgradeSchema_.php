<?php 

namespace 'Vendor\Module\Setup';

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;
class UpgradeSchema implements InstallSchemaInterface {

	
	public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context){

		$installer = $setup->startSetup();

		if(version_compare($context->getVersion(), '1.0.1') < 0){
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

}