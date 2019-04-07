<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_GiftCard
 */

namespace Amasty\GiftCard\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * UpgradeData constructor
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), '1.0.5', '<')) {
            $this->addWeightAttribute($eavSetup);
        }

        if (version_compare($context->getVersion(), '1.1.0', '<')) {
            $this->addGiftCardType($eavSetup);
        }

        $setup->endSetup();
    }

    /**
     * @param EavSetup $eavSetup
     */
    private function addWeightAttribute(EavSetup $eavSetup)
    {
        // make 'weight' attribute applicable to gift card products
        $applyTo = $eavSetup->getAttribute('catalog_product', 'weight', 'apply_to');
        if ($applyTo) {
            $applyTo = explode(',', $applyTo);
            if (!in_array('amgiftcard', $applyTo)) {
                $applyTo[] = 'amgiftcard';
                $eavSetup->updateAttribute('catalog_product', 'weight', 'apply_to', join(',', $applyTo));
            }
        }
    }

    private function addGiftCardType(EavSetup $eavSetup)
    {
        $attributeGroupName = 'Gift Card Information';
        $entityType = ProductAttributeInterface::ENTITY_TYPE_CODE;
        $eavSetup->addAttributeGroup($entityType, 'Default', $attributeGroupName, 9);

        $eavSetup->addAttribute(
            $entityType,
            'am_gift_card_type',
            [
                'type' => 'int',
                'label' => '',
                'backend' => '',
                'input' => '',
                'source' => '',
                'required' => false,
                'sort_order' => -30,
                'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                'group' => '',
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'apply_to' => 'amgiftcard',
                'class' => 'validate-number',
                'visible' => false,
                'used_in_product_listing' => true,
            ]
        );
    }
}
