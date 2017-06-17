<?php

class Freento_GroupAutoSwitch_Adminhtml_System_ConfigController extends Mage_Adminhtml_Controller_Action
{
    
    public function forceFillAction()
    {
        $config = Mage::getModel('core/config_data')->load(Freento_GroupAutoSwitch_Model_Cron::FORCE_FILL_LAST_ID_PATH, 'path');
        
        if(!$config->getConfigId()) {
            $config->setPath(Freento_GroupAutoSwitch_Model_Cron::FORCE_FILL_LAST_ID_PATH)->setValue('-1')->save();
        }
        
        if($config->getValue() >= 0) {
            $result = array(
                'error' => 1,
                'message' => Mage::helper('freento_groupautoswitch')->__('Customer validation already running')
            );
        } else {
            $config->setValue(0)->save();
            $result = array(
                'error' => 0,
                'message' => Mage::helper('freento_groupautoswitch')->__('Customer validation started, it will be finished soon')
            );
        }
        
        echo json_encode($result);
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/freento_groupautoswitch');
    }
    
}