<?php

class Freento_Groupautoswitch_Model_Observer
{
    public function fillLastGroupChangeDate($observer)
    {
        $customer = $observer->getCustomer();
        if ($customer->getData('group_id') != $customer->getOrigData('group_id')) {
            $customer->setData('group_last_change_date', Mage::getModel('core/date')->gmtDate());
        }
    }
    
    public function checkGroupAutoSwitch($observer)
    {
        try {
            $order = $observer->getOrder();
            /* If order invoiced sum changed, then run validation */
            if ($order->getData('base_total_invoiced') != $order->getOrigData('base_total_invoiced')) {
                $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
                
                if(!$customer->getEmail()) {
                    $customer->setEmail($order->getCustomerEmail());
                }
                $observerObject = new Varien_Object(array('customer' => $customer));

                Mage::getModel('freento_groupautoswitch/group')
                        ->getCollection()
                        ->validateAndSwitch($observerObject);
            }
        } catch (Exception $ex) { /* TODO: Add new Exception type for extension and catch it */
            
        }
    }
}
