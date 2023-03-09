<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Shipping Table Rates
*/
$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE `{$this->getTable('amtable/rate')}` CHANGE `time_delivery` `time_delivery` VARCHAR(255) NULL DEFAULT NULL;
");