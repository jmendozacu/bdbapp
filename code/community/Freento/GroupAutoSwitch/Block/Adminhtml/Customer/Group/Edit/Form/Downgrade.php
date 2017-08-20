<?php

class Freento_GroupAutoSwitch_Block_Adminhtml_Customer_Group_Edit_Form_Downgrade extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $customerGroup = Mage::registry('current_group');
        $model = Mage::helper('freento_groupautoswitch')->getAutoSwitchInfo($customerGroup);
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('freento_groupautoswitch')->__('Downgrade')));
        
        $fieldset->addField('downgrade_after', 'text', array(
            'label'     => Mage::helper('freento_groupautoswitch')->__('Downgrade After (days without purchase)'),
            'title'     => Mage::helper('freento_groupautoswitch')->__('Downgrade After (days without purchase)'),
            'name'      => 'downgrade_after',
        ));
        
        $fieldset->addField('downgrade_group', 'select', array(
            'label'     => Mage::helper('freento_groupautoswitch')->__('Downgrade to Group'),
            'title'     => Mage::helper('freento_groupautoswitch')->__('Downgrade to Group'),
            'name'      => 'downgrade_group',
            'values'    =>  $this->_getAllCustomerGroupOptions()
        ));
        
        $fieldset->addField('downgrade_template', 'select', array(
            'label'     => Mage::helper('freento_groupautoswitch')->__('Downgrade Email Template'),
            'title'     => Mage::helper('freento_groupautoswitch')->__('Downgrade Email Template'),
            'name'      => 'downgrade_template',
            'values'    => Mage::getModel('adminhtml/system_config_source_email_template')->toOptionArray()
        ));
        
        $fieldset->addField('notify_customer_before_downgrade', 'text', array(
            'label'     => Mage::helper('freento_groupautoswitch')->__('Notify Customer Before Downgrade (days)'),
            'title'     => Mage::helper('freento_groupautoswitch')->__('Notify Customer Before Downgrade (days)'),
            'name'      => 'notify_customer_before_downgrade',
        ));
        
        $fieldset->addField('notify_customer_before_downgrade_template', 'select', array(
            'label'     => Mage::helper('freento_groupautoswitch')->__('Notify Customer Before Downgrade Template'),
            'title'     => Mage::helper('freento_groupautoswitch')->__('Notify Customer Before Downgrade Template'),
            'name'      => 'notify_customer_before_downgrade_template',
            'values'    => Mage::getModel('adminhtml/system_config_source_email_template')->toOptionArray()
        ));
        
        $form->setValues($model);
        $this->setForm($form);
        return parent::_prepareForm();
    }
    
    protected function _getAllCustomerGroupOptions(){
        $all = Mage::getModel('customer/group')->getCollection();
        $options = array('-1' => 'No changes');
        foreach($all as $group){
            /* Skip Not logged in group */
            if ($group->getId() == 0) {
                continue;
            }
            $options[$group->getId()] = $group->getData('customer_group_code');
        }
        return $options;
    }
}
