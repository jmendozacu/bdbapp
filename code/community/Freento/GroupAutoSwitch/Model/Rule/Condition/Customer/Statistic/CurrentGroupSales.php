<?php

class Freento_GroupAutoSwitch_Model_Rule_Condition_Customer_Statistic_CurrentGroupSales extends Freento_GroupAutoSwitch_Model_Rule_Condition_Customer_Statistic_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setType('freento_groupautoswitch/rule_condition_customer_statistic_currentGroupSales')
            ->setValue(null);
    }
    
    public function loadAttributeOptions()
    {
        $hlp = Mage::helper('freento_groupautoswitch');
        $this->setAttributeOption(array(
            'currentGroupSales' => $hlp->__('Customer Current Group Sales'),
        ));
        return $this;
    }
    
    public function validate(Varien_Object $observerObject)
    {
        $customer = $observerObject->getCustomer();
        
        $orderCollection = Mage::getModel('sales/order')->getCollection();
        $select = $orderCollection->getSelect();
        $select
                ->where('customer_id = ?', $customer->getId())
                ->columns(array('sum_invoiced' => 'sum(base_total_invoiced)'))
                ->columns(array('sum_refunded' => 'sum(base_total_refunded)'))
                ->group('customer_id');
        if ($customer->getData('group_last_change_date')) {
            $groupLastChangePlusMinute = date(Varien_Date::DATETIME_PHP_FORMAT, strtotime('+1 minutes', strtotime($customer->getData('group_last_change_date'))));
            $select->where('created_at >= ?', $groupLastChangePlusMinute);
        }
        $statistic = $orderCollection->getFirstItem();
        
        return $this->validateAttribute($statistic->getSumInvoiced() - $statistic->getSumRefunded());
    }
}
