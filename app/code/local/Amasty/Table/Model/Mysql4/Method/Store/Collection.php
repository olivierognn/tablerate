<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Shipping Table Rates
*/

class Amasty_Table_Model_Mysql4_Method_Store_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('amtable/method_store');
    }
}
