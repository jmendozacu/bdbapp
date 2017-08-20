<?php

class Freento_GroupAutoSwitch_Block_Adminhtml_Customer_Group_Edit_Form_Conditions extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $helper = Mage::helper('freento_groupautoswitch');
        $customerGroup = Mage::registry('current_group');
        $model = Mage::helper('freento_groupautoswitch')->getAutoSwitchInfo($customerGroup);

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('rule_');

        $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
            ->setTemplate('promo/fieldset.phtml')
            ->setNewChildUrl($this->getUrl('*/*/newConditionHtml/form/rule_conditions_fieldset'));

        $fieldset = $form->addFieldset('conditions_fieldset', array(
            'legend' => $helper->__('Conditions'))
        )->setRenderer($renderer);

        $fieldset->addField('conditions', 'text', array(
            'name' => 'conditions',
            'label' => $helper->__('Conditions'),
            'title' => $helper->__('Conditions'),
            'required' => true,
        ))->setRule($model)->setRenderer(Mage::getBlockSingleton('rule/conditions'));

        $form->setValues($model);

        $this->setForm($form);
        return parent::_prepareForm();
    }
}