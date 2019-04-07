<?php 

namespace Brownbag\Presentation\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
/*
	method: install
*/
use Magento\Framework\Setup\ModuleContextInterface; 
/* 
	method: getVersion
*/
use Magento\Framework\Setup\SchemaSetupInterface; 
/* 
	method: getIdxName, getFkName
*/	
use Magento\Framework\DB\Ddl\Table;
use Brownbag\Presentation\Model\Presentation as Model;
use Brownbag\Presentation\Model\ResourceModel\Presentation as ResourceModel;

class InstallSchema implements InstallSchemaInterface {

	public function install(SchemaSetupInterface $setup, ModuleContextInterface $context){

		$installer = $setup->startSetup();

		// checks if table not exists
		if(!$installer->tableExists(ResourceModel::MAIN_TABLE)){
			// get database connection
			$table = $installer->getConnection()
				// create new table
				->newTable(
					$installer->getTable(ResourceModel::MAIN_TABLE)
				)
				// add column
				->addColumn(
					Model::ID, // column name 
					Table::TYPE_INTEGER, // column data type
					null,
					[
						'identity' =>true,
						'nullable' =>false,
						'primary'  =>true,
						'unsigned' => true

					],
					'Repository Presentation ID' // column alias
				)
				->addColumn(
					Model::DATE,
					Table::TYPE_TIMESTAMP,
					null,
					[
						'nullable' => false, 
						'default' => Table::TIMESTAMP_INIT
					],
					'Created At'
				)
				->addColumn(
					Model::CONTENT,
					Table::TYPE_TEXT,
					'2M',
					[
						'nullable' => false, 
						'default' => ''
					],
					'Content'
				)
				->addColumn(
				    'image',
				    Table::TYPE_TEXT,
				    255,
				    array(
				        'nullable'  => false,
				    ),
				    'Image'
				)
				->setComment('Repository Loggers Table');
				$installer->getConnection()->createTable($table);	
		}

		$installer->endSetup();

	}

} 