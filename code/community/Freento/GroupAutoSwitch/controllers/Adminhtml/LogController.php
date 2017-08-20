<?php

class Freento_GroupAutoSwitch_Adminhtml_LogController extends Mage_Adminhtml_Controller_Action
{
    
    public function indexAction()
    {
        $this
            ->loadLayout()
            ->_setActiveMenu('customer')
            ->renderLayout();
    }
    
    
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('freento_groupautoswitch/adminhtml_log_grid')->toHtml()
        );
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('customer/freento_groupautoswitch_log');
    }
}