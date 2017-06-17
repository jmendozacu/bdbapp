<?php

class Freento_Groupautoswitch_Model_Resource_Group_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('freento_groupautoswitch/group');
    }
    
    public function validateAndSwitch($observerObject)
    {
        $customer = $observerObject->getCustomer();
        $this
            ->addFieldToFilter('is_enabled', 1)
            ->setOrder('priority', 'desc');
        foreach ($this as $autoswitchGroup) {
            if ($customer->getGroupId() != $autoswitchGroup->getGroupId()) {
                if ($autoswitchGroup->validate($observerObject)) {
                    $autoswitchGroup->switchGroup($customer);
                    if ($autoswitchGroup->getData('stop_further_rules')) {
                        break;
                    }
                }
            }
        }
        return $this;
    }
}
