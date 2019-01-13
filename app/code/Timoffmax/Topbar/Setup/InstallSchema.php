<?php

namespace Timoffmax\Topbar\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        // Setup main table with topbars
        $topbarTable = $installer->getConnection()->newTable(
            $installer->getTable('timoffmax_topbar')
        )
        ->addColumn(
            'topbar_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
            'ID'
        )
        ->addColumn(
            'title',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Title'
        )
        ->addColumn(
            'content',
            Table::TYPE_TEXT,
            5000,
            ['nullable' => false],
            'Content'
        )
        ->addColumn(
            'bg_color',
            Table::TYPE_TEXT,
            7,
            ['nullable' => false],
            'Background Color'
        )
        ->addColumn(
            'priority',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Priority'
        )
        ->addColumn(
            'is_active',
            Table::TYPE_INTEGER,
            1,
            ['nullable' => false, 'default' => 0],
            'Is Active'
        )
        ->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            [ 'nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Creation Time'
        )
        ->addColumn(
            'updated_at',
            Table::TYPE_TIMESTAMP,
            null,
            [ 'nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
            'Modification Time'
        );

        // Setup table of relations with storeviews (many to many)
        $topbarStoreTable = $installer->getConnection()->newTable(
            $installer->getTable('timoffmax_topbar_store')
        )
        ->addColumn(
            'topbar_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Topbar ID'
        )
        ->addColumn(
            'store_id',
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Store ID'
        )
        ->addIndex(
            $setup->getIdxName('timoffmax_topbar_store', ['store_id']),
            ['store_id']
        )
        ->addForeignKey(
            $setup->getFkName('timoffmax_topbar_store', 'topbar_id', 'timoffmax_topbar', 'id'),'topbar_id',
            $setup->getTable('timoffmax_topbar'),'topbar_id', Table::ACTION_CASCADE
        )
        ->addForeignKey(
            $setup->getFkName('timoffmax_topbar_store', 'store_id', 'store', 'store_id'),'store_id',
            $setup->getTable('store'),'store_id', Table::ACTION_CASCADE
        )
        ->setComment(
            'Timoffmax_Topbar To Store Linkage Table'
        );

        // Create tables
        $installer->getConnection()->createTable($topbarTable);
        $installer->getConnection()->createTable($topbarStoreTable);

        $installer->endSetup();
    }
}
