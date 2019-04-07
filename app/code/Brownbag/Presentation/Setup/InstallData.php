<?php

namespace  Brownbag\Presentation\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallData implements InstallDataInterface
{

    /**
     * Install data
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $data = [
                [
                    'repository_content' => 'This is the first description.'
                ],
                [
                    'repository_content' => 'This is the second description.',
                ],
                [
                    'repository_content' => 'Here we have a slightly longer description.',
              
                ],
                [
                    'repository_content' => 'This is the fourth description.',
                ],
                [
                    'repository_content' => 'The quick brown fox jumped over the lazy dog.',
                ]
            ];

            foreach ($data as $datum) {
                $setup->getConnection()
                    ->insertForce($setup->getTable('brownbag_presentation'), $datum);
            }
        }
    }
}
