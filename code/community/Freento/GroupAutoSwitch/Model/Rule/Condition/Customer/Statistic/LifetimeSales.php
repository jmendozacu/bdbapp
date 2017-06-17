<?php

class Freento_GroupAutoSwitch_Model_Rule_Condition_Customer_Statistic_LifetimeSales extends Freento_GroupAutoSwitch_Model_Rule_Condition_Customer_Statistic_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->setType('freento_groupautoswitch/rule_condition_customer_statistic_lifetimeSales')
            ->setValue(null);
    }
    
    public function loadAttributeOptions()
    {
        $hlp = Mage::helper('freento_groupautoswitch');
        $this->setAttributeOption(array(
            'lifetimeSales' => $hlp->__('Customer Lifetime Sales'),
        ));
        return $this;
    }
    
    public function validate(Varien_Object $observerObject)
    {
        $customer = $observerObject->getCustomer();
        
        $orderCollection = Mage::getModel('sales/order')->getCollection();
        $orderCollection
                ->getSelect()
                ->where('customer_id = ?', $customer->getId())
                ->columns(array('sum_invoiced' => 'sum(base_total_invoiced)'))
                ->columns(array('sum_refunded' => 'sum(base_total_refunded)'))
                ->group('customer_id');
        $statistic = $orderCollection->getFirstItem();
        
        return $this->validateAttribute($statistic->getSumInvoiced() - $statistic->getSumRefunded());
    }
}
