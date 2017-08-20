<?php

class Freento_GroupAutoSwitch_Block_Adminhtml_Customer_Group_Edit_Form_GroupSettings extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $customerGroup = Mage::registry('current_group');
        $model = Mage::helper('freento_groupautoswitch')->getAutoSwitchInfo($customerGroup);
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('freento_groupautoswitch')->__('Group Settings')));
        
        $fieldset->addField('notify_on_group_change_template', 'select', array(
            'label'     => Mage::helper('freento_groupautoswitch')->__('Notify Customer After Group Change Template'),
            'title'     => Mage::helper('freento_groupautoswitch')->__('Notify Customer After Group Change Template'),
            'name'      => 'notify_on_group_change_template',
            'values'    => Mage::getModel('adminhtml/system_config_source_email_template')->toOptionArray()
        ));
        
        $fieldset->addField('text_for_customer', 'text', array(
            'label'     => Mage::helper('freento_groupautoswitch')->__('Text for Customer'),
            'title'     => Mage::helper('freento_groupautoswitch')->__('Text for Customer'),
            'name'      => 'text_for_customer',
        ));
        
        $form->setValues($model);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
