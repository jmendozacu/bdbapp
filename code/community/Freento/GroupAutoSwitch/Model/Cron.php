<?php

class Freento_Groupautoswitch_Model_Cron
{
    
    const FORCE_FILL_LAST_ID_PATH = 'freento_groupautoswitch/force_fill/last_id';
    const FORCE_FILL_VALIDATE_CUSTOMERS_QTY = 100;
    
    public function downgradeCheck()
    {
        $collection = Mage::getModel('freento_groupautoswitch/group')->getCollection()->addFieldToFilter('is_enabled', 1);
        foreach ($collection as $item) {
            $item->downgradeCheck();
        }
    }
    
    public function downgradeBeforeEmail()
    {
        $collection = Mage::getModel('freento_groupautoswitch/group')->getCollection()->addFieldToFilter('is_enabled', 1);
        foreach ($collection as $item) {
            $item->downgradeBeforeEmail();
        }
    }
    
    public function forceFill() {
        $config = Mage::getModel('core/config_data')->load(self::FORCE_FILL_LAST_ID_PATH, 'path');
        
        if((int)$config->getValue() < 0) {
            return;
        }
        
        $customersCollection = Mage::getModel('customer/customer')->getCollection()
            ->setPageSize(self::FORCE_FILL_VALIDATE_CUSTOMERS_QTY)
            ->setCurPage(1)
            ->addFieldToFilter('entity_id', array('gt' => $config->getValue()));
        
        if($customersCollection->getSize() == 0) {
            $config->setValue('-1')->save();
            return;
        }
        
        foreach($customersCollection as $customer) {
            $observerObject = new Varien_Object(array('customer' => $customer));
            Mage::getModel('freento_groupautoswitch/group')->getCollection()->validateAndSwitch($observerObject);
            $config->setValue($customer->getId())->save();
        }
        
    }
    
}
