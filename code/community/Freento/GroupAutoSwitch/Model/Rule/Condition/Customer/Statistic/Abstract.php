<?php

class Freento_GroupAutoSwitch_Model_Rule_Condition_Customer_Statistic_Abstract extends Mage_Rule_Model_Condition_Abstract
{
    public function getInputType()
    {
        return 'string';
    }

    public function loadOperatorOptions()
    {
        $this->setOperatorOption(array(
            '==' => Mage::helper('rule')->__('is'),
            '!=' => Mage::helper('rule')->__('is not'),
            '>=' => Mage::helper('rule')->__('equals or greater than'),
            '<=' => Mage::helper('rule')->__('equals or less than'),
            '>' => Mage::helper('rule')->__('greater than'),
            '<' => Mage::helper('rule')->__('less than'),
        ));
        return $this;
    }

    public function getValueElementType()
    {
        return 'text';
    }

    public function getAttributeElement()
    {
        $element = parent::getAttributeElement();
        $element->setShowAsText(true);
        return $element;
    }
}
