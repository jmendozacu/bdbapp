<?php

class Freento_GroupAutoSwitch_Block_Adminhtml_Log extends Mage_Core_Block_Template
{
    
    public function __construct()
    {
        $this->_blockGroup = 'freento_groupautoswitch';
        $this->_controller = 'adminhtml_log';
        $this->_headerText = Mage::helper('freento_groupautoswitch')->__('Group auto swicth log');
        
        parent::__construct();
        
        $this->_removeButton('add');
    }
    
}