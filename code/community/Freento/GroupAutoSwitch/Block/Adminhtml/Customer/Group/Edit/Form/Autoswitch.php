<?php

class Freento_GroupAutoSwitch_Block_Adminhtml_Customer_Group_Edit_Form_Autoswitch extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $customerGroup = Mage::registry('current_group');
        $model = Mage::helper('freento_groupautoswitch')->getAutoSwitchInfo($customerGroup);
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('freento_groupautoswitch')->__('Autoswitch Configuration')));

        $fieldset->addField('is_enabled', 'select', array(
            'label'     => Mage::helper('freento_groupautoswitch')->__('Enable'),
            'title'     => Mage::helper('freento_groupautoswitch')->__('Enable'),
            'name'      => 'is_enabled',
            'options'    => array(
                '1' => Mage::helper('freento_groupautoswitch')->__('Yes'),
                '0' => Mage::helper('freento_groupautoswitch')->__('No'),
            ),
        ));
        
        if (! Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('store_id', 'select', [
                'name'      => 'stores[]',
                'label'     => $this->__('Store View'),
                'title'     => $this->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ]);
            $field->setRenderer($this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element'));
        } else {
            $fieldset->addField('store_id', 'hidden', [
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ]);
        }
        
        $fieldset->addField('priority', 'text', array(
            'label'     => Mage::helper('freento_groupautoswitch')->__('Rules Priority'),
            'title'     => Mage::helper('freento_groupautoswitch')->__('Rules Priority'),
            'name'      => 'priority',
        ));
        
        $fieldset->addField('stop_further_rules', 'select', array(
            'label'     => Mage::helper('freento_groupautoswitch')->__('Stop Further Rules Processing'),
            'title'     => Mage::helper('freento_groupautoswitch')->__('Stop Further Rules Processing'),
            'name'      => 'stop_further_rules',
            'options'    => array(
                '1' => Mage::helper('freento_groupautoswitch')->__('Yes'),
                '0' => Mage::helper('freento_groupautoswitch')->__('No'),
            ),
        ));

        $form->setValues($model);

        $this->setForm($form);

        return parent::_prepareForm();
    }
}
