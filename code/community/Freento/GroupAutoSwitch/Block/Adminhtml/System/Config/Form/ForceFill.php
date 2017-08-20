<?php

class Freento_GroupAutoSwitch_Block_Adminhtml_System_Config_Form_ForceFill extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('freento_groupautoswitch/system/config/forceFill.phtml');
        }
        return $this;
    }
    
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }
    
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $originalData = $element->getOriginalData();
        $this->addData(array(
            'button_label' => Mage::helper('freento_groupautoswitch')->__($originalData['button_label']),
            'html_id' => $element->getHtmlId(),
            'ajax_url' => Mage::getSingleton('adminhtml/url')->getUrl('freento_groupautoswitch/adminhtml_system_config/forceFill')
        ));

        return $this->_toHtml();
    }
    
}