<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Shipping Table Rates
*/ 
class Amasty_Table_Model_Mysql4_Rate extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('amtable/rate', 'rate_id');
    }

    /**
     * @param int $methodId
     * @param array $data
     *
     * @return string
     */
    public function batchInsert($methodId, $data)
    {
        $resultArray = array();
        $err = '';
        foreach ($data as $rate) {
            array_unshift($rate, (int)$methodId);
            $resultArray[] = $rate;
        }
        $arrayWithFields = array(
            'method_id',
            'country',
            'state',
            'city',
            'zip_from',
            'zip_to',
            'price_from',
            'price_to',
            'weight_from',
            'weight_to',
            'qty_from',
            'qty_to',
            'shipping_type',
            'cost_base',
            'cost_percent',
            'cost_product',
            'cost_weight',
            'time_delivery',
            'num_zip_from',
            'num_zip_to'
        );

        try {
            $this->_getWriteAdapter()->insertArray($this->getMainTable(), $arrayWithFields, $resultArray);
        } catch (\Exception $e) {
            $err = $e->getMessage();
        }

        return $err;
    }
    
    public function deleteBy($methodId)
    {
        $this->_getWriteAdapter()->delete($this->getMainTable(), 'method_id=' . intVal($methodId)); 
    }     
       
}