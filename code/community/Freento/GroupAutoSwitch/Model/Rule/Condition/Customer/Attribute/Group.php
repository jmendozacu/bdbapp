<?php

class Freento_GroupAutoSwitch_Model_Rule_Condition_Customer_Attribute_Group extends Mage_Rule_Model_Condition_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setType('freento_groupautoswitch/rule_condition_customer_attribute_group')
            ->setValue(null);
    }
    
    public function loadOperatorOptions()
    {
        $this->setOperatorOption(array(
            '()'    => Mage::helper('rule')->__('is one of'),
            '!()'   => Mage::helper('rule')->__('is not one of'),
        ));
        return $this;
    }
    
    public function loadAttributeOptions()
    {
        $hlp = Mage::helper('freento_groupautoswitch');
        $this->setAttributeOption(array(
            'group' => $hlp->__('Customer Group'),
        ));
        return $this;
    }
    
    public function validate(Varien_Object $observerObject)
    {
        $customer = $observerObject->getCustomer();
        
        return $this->validateAttribute($customer->getGroupId());
    }
    
    public function getValueElementType()
    {
        return 'multiselect';
    }
    
    public function getValueSelectOptions()
    {
        return Mage::getResourceModel('customer/group_collection')->toOptionArray();
    }
}