<?php

class Freento_GroupAutoSwitch_Block_Adminhtml_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    
    public function __construct()
    {
        parent::__construct();
        $this->setId('freentoGroupAutoSwitchLogGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
   
    protected function _prepareCollection()
    {
        $this->setCollection(Mage::getModel('freento_groupautoswitch/log')->getCollection());
        
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => $this->__('ID'),
            'type' => 'number',
            'index' => 'id',
            'align' => 'right',
            'width' => '50px',
        ));
        
        $this->addColumn('details', array(
            'header' => $this->__('Details'),
            'type' => 'text',
            'index' => 'details',
        ));
        
        $this->addColumn('created_at', array(
            'header'    => $this->__('Created At'),
            'align'     => 'right',
            'width'     => '150px',
            'type'      => 'datetime',
            'index'     => 'created_at',
            'filter_index' => 'created_at',
        ));
        
        return $this;
    }
    
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
    
}
