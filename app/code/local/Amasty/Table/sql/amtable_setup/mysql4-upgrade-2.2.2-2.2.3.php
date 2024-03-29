<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Shipping Table Rates
*/
$installer = $this;

$installer->startSetup();

$installer->run("
  ALTER TABLE  `{$this->getTable('amtable/method')}`  ADD  `max_rate`  decimal(12,2) unsigned NOT NULL default '0' AFTER  `select_rate`;
  ALTER TABLE `{$this->getTable('amtable/method')}` ADD `min_rate` decimal(12,2) unsigned NOT NULL default '0' AFTER `select_rate`;
");