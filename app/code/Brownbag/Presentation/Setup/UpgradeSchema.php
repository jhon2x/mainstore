<?php 

namespace Brownbag\Presentation\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Brownbag\Presentation\Model\Presentation as Model;
use Brownbag\Presentation\Model\ResourceModel\Presentation as ResourceModel;

class UpgradeSchema implements UpgradeSchemaInterface {

	public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context){

		$installer = $setup->startSetup();

		if (version_compare($context->getVersion(), '1.0.1') < 0) {
			if (!$installer->tableExists(ResourceModel::MAIN_TABLE)) {
			    $table = $installer->getConnection()
			    	->newTable(
			    		$installer->getTable(ResourceModel::MAIN_TABLE)
			    	)
			    	->addColumn(
			    		Model::ID,
			    		Table::TYPE_INTEGER,
			    		null,
			    		[
			    			'identity' =>true,
			    			'nullable' =>false,
			    			'primary'  =>true,
			    			'unsigned' => true

			    		],
			    		'Repository Presentation ID'
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
		}	

		$installer->endSetup();
	}

} 