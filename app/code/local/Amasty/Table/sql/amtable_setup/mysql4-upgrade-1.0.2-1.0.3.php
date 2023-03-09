<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Shipping Table Rates
*/ 
$this->startSetup();

$this->run("

ALTER TABLE  `{$this->getTable('amtable/rate')}` 
ADD `cost_weight` DECIMAL( 12, 2 ) NOT NULL DEFAULT '0.00' AFTER `cost_product` 

");

$this->endSetup(); 