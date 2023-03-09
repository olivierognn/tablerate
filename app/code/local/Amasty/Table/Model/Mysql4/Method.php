<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Shipping Table Rates
*/ 
class Amasty_Table_Model_Mysql4_Method extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('amtable/method', 'method_id');
    }          
}