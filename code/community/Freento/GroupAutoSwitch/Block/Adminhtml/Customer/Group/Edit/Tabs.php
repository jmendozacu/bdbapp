<?php

class Freento_Groupautoswitch_Block_Adminhtml_Customer_Group_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('id');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('freento_groupautoswitch')->__('Customer Group'));
    }

    protected function _beforeToHtml()
    {
        $helper = Mage::helper('freento_groupautoswitch');

        $this->addTab('main_section', array(
            'label'     => $helper->__('Main'),
            'title'     => $helper->__('Main'),
            'content'   => $this->getLayout()->createBlock('freento_groupautoswitch/adminhtml_customer_group_edit_form_main')->toHtml(),
            'active'    => true
        ));
        
        $this->addTab('autoswitch_section', array(
            'label'     => $helper->__('Autoswitch Configuration'),
            'title'     => $helper->__('Autoswitch Configuration'),
            'content'   => $this->getLayout()->createBlock('freento_groupautoswitch/adminhtml_customer_group_edit_form_autoswitch')->toHtml(),
        ));

        $this->addTab('conditions_section', array(
            'label'     => $helper->__('Conditions'),
            'title'     => $helper->__('Conditions'),
            'content'   => $this->getLayout()->createBlock('freento_groupautoswitch/adminhtml_customer_group_edit_form_conditions')->toHtml(),
        ));
        
        $this->addTab('group_settings', array(
            'label'     => $helper->__('Group Settings'),
            'title'     => $helper->__('Group Settings'),
            'content'   => $this->getLayout()->createBlock('freento_groupautoswitch/adminhtml_customer_group_edit_form_groupSettings')->toHtml(),
        ));
        
        $this->addTab('downgrade', array(
            'label'     => $helper->__('Downgrade'),
            'title'     => $helper->__('Downgrade'),
            'content'   => $this->getLayout()->createBlock('freento_groupautoswitch/adminhtml_customer_group_edit_form_downgrade')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

}