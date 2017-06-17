<?php

class Freento_Groupautoswitch_Model_Resource_Group extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct() 
    {
        $this->_init('freento_groupautoswitch/group', 'id');     
    }
}
