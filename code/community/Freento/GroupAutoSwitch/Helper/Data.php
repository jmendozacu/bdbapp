<?php

class Freento_Groupautoswitch_Helper_Data extends Mage_Core_Helper_Data
{
    public function getAutoSwitchInfo($customerGroup)
    {
        if (!$customerGroup->getAutoSwitchInfo()) {
            $customerGroup->setAutoSwitchInfo(Mage::getModel('freento_groupautoswitch/group')->load($customerGroup->getId(), 'group_id'));
        }
        return $customerGroup->getAutoSwitchInfo();
    }
}
