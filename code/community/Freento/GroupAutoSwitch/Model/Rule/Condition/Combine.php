<?php

class Freento_GroupAutoSwitch_Model_Rule_Condition_Combine extends Mage_Rule_Model_Condition_Combine
{
    public function __construct()
    {
        parent::__construct();
        $this->setType('freento_groupautoswitch/rule_condition_combine');
    }

    public function getNewChildSelectOptions()
    {
        $customerStatistic = array();
        $customerStatistic[] = array('value' => 'freento_groupautoswitch/rule_condition_customer_statistic_lifetimeSales', 'label' => Mage::helper('freento_groupautoswitch')->__('Customer Lifetime Sales'));
        $customerStatistic[] = array('value' => 'freento_groupautoswitch/rule_condition_customer_statistic_currentGroupSales', 'label' => Mage::helper('freento_groupautoswitch')->__('Customer Current Group Sales'));
        
        $customerAttributes = array();
        $customerAttributes[] = array('value' => 'freento_groupautoswitch/rule_condition_customer_attribute_group', 'label' => Mage::helper('freento_groupautoswitch')->__('Group'));
        
        $conditions = parent::getNewChildSelectOptions();
        $conditions = array_merge_recursive($conditions, array(
            array('value' => 'freento_groupautoswitch/rule_condition_combine', 'label' => Mage::helper('catalogrule')->__('Conditions Combination')),
            array('label' => Mage::helper('freento_groupautoswitch')->__('Customer Statistic'), 'value' => $customerStatistic),
            array('label' => Mage::helper('freento_groupautoswitch')->__('Customer Attributes'), 'value' => $customerAttributes),
        ));
        return $conditions;
    }
}
