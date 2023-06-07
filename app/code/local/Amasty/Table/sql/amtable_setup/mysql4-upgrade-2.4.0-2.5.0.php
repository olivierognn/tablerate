<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Shipping Table Rates
*/

$installer = $this;

$installer->startSetup();
$installer->getConnection()->addColumn(
    $installer->getTable('amtable/method'),
    'comment_img',
    "VARCHAR(255) DEFAULT NULL"
);

$installer->endSetup();
