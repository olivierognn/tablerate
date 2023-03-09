<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2022 Amasty (https://www.amasty.com)
* @package Shipping Table Rates
*/

class Amasty_Table_Model_Weight extends Mage_Core_Model_Abstract
{
    const PATTERN_VALID_VOLUME_DIMENSION = '/^((?:\d+?)(?:[.,](?:\d+?)(?=[^\d.,\s]))?)(?:[^\d.,\s])((?:\d+?)(?:[.,](?:\d+?)(?=[^\d.,\s]))?)(?:[^\d.,\s])((?:\d+?)(?:[.,](?:\d+?))?)$/';
    const VOLUMETRIC_WEIGHT_ATTRIBUTE_TYPE = 'volumetric_weight_attribute';
    const VOLUME_ATTRIBUTE_TYPE = 'volume_attribute';
    const VOLUMETRIC_DIMENSIONS_ATTRIBUTE_TYPE = 'dimmensions_attribute';
    const VOLUMETRIC_SEPARATE_DIMENSION_ATTRIBUTE_TYPE= 'separate_dimmension_attribute';

    /**
     * @param Mage_Sales_Model_Quote_Item $item
     * @return float|int
     */
    public function getItemWeight($item)
    {
        $volumetricWeightValue = $this->calculateVolumetricWeight($item);

        return $volumetricWeightValue && ($volumetricWeightValue > $item->getWeight()) ?
            $volumetricWeightValue :
            $item->getWeight();
    }

    /**
     * @param Mage_Sales_Model_Quote_Item $item
     * @return float|int
     */
    public function calculateVolumetricWeight($item)
    {
        $helper = Mage::helper('amtable');
        $volumetricWeight = 0;
        $attributeCodes = array();

        switch ($helper->getVolumetricWeighType()) {
            case self::VOLUMETRIC_WEIGHT_ATTRIBUTE_TYPE:
                $attributeCode = $helper->getVolumetricWeightAttribute();
                $volumetricWeight = (float) $this->attributeResolver($item, $attributeCode);
                break;

            case self::VOLUME_ATTRIBUTE_TYPE:
                $attributeCode = $helper->getVolumeAttribute();
                $volumetricWeight =
                    (float) $this->attributeResolver($item, $attributeCode) /
                    $helper->getShippingFactor();
                break;

            case self::VOLUMETRIC_DIMENSIONS_ATTRIBUTE_TYPE:
                $attributeCode = $helper->getDimensionsAttribute();
                $dimensions = $this->attributeResolver($item, $attributeCode);
                $volumetricWeight = $this->calculateVolumeByDimensionsAttribute($dimensions) /
                    $helper->getShippingFactor();
                break;

            case self::VOLUMETRIC_SEPARATE_DIMENSION_ATTRIBUTE_TYPE:
                $attributeCodes[] = $helper->getFirstDimensionsAttribute();
                $attributeCodes[] = $helper->getSecondDimensionsAttribute();
                $attributeCodes[] = $helper->getThirdDimensionsAttribute();
                $volumetricWeight = $this->getVolume($item, $attributeCodes) /
                    $helper->getShippingFactor() ;
                break;
        }

        return $volumetricWeight;
    }

    /**
     * @param Mage_Sales_Model_Quote_Item $item
     * @param string $attributeCode
     *
     * @return float|string
     */
    protected function attributeResolver($item, $attributeCode)
    {
        $value = $item->getData($attributeCode);
        if ($value === null) {
            $product = $item->getProduct();
            $value = $product->getData($attributeCode);
            if ($value === null) {
                $value = Mage::getResourceModel('catalog/product')
                    ->getAttributeRawValue($item->getProductId(), $attributeCode, $item->getStore());
                $product->setData($attributeCode, $value);
            }
        }

        return $value;
    }

    /**
     * @param Mage_Sales_Model_Quote_Item $item
     * @param array $attributeCodes
     *
     * @return float|int
     */
    public function getVolume($item, $attributeCodes)
    {
        $volume = 1;

        foreach ($attributeCodes as $attributeCode) {
            $volume *= (float) $this->attributeResolver($item, $attributeCode);
        }

        return $volume;
    }

    /**
     * @param string $dimensions
     * @return float
     */
    public function calculateVolumeByDimensionsAttribute($dimensions = '')
    {
        $volume = 0;
        if ($this->isVolumeDimensions($dimensions)) {
            $dimensionNumbers = array();
            preg_match(self::PATTERN_VALID_VOLUME_DIMENSION, $dimensions, $dimensionNumbers);
            array_shift($dimensionNumbers);

            if (!empty($dimensionNumbers)) {
                $volume = 1;
                foreach ($dimensionNumbers as $number) {
                    $number = str_replace(',', '.', $number);
                    $volume *= (float)$number;
                }
            }
        }

        return (float) $volume;
    }

    /**
     * @param string $dimensions
     * @return bool
     */
    protected function isVolumeDimensions($dimensions = '')
    {
        return Zend_Validate::is($dimensions, 'Regex', array(self::PATTERN_VALID_VOLUME_DIMENSION));
    }
}
