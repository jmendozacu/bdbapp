<?php

class Freento_Groupautoswitch_Model_Resource_Log extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct() 
    {
        $this->_init('freento_groupautoswitch/log', 'id');     
    }
    
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!$object->hasCreatedAt()) {
            $object->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
        }
    }
    
}
