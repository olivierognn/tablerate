<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Shipping Table Rates
*/
$installer = $this;

$installer->startSetup();

$installer->run("
  ALTER TABLE  `{$this->getTable('amtable/rate')}`  ADD  `time_delivery` INT( 10 ) NOT NULL DEFAULT '0' AFTER  `qty_to`;
");