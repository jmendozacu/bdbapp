<?php

class Freento_GroupAutoSwitch_Test_Model_Group extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Switch group for existing customer
     *
     * @test
     * @loadFixture
     * @loadExpectation
     * @dataProvider dataProvider
     */
    public function switchGroup($testId, $customerId, $newGroupId)
    {
        
        if($this->_getExpectations()->getData($testId . '/email_sent')) {
            $this->_checkEmailSent('once');
            $this->_checkEmailTemplate($testId);
        } else {
            $this->_checkEmailSent('never');
        }
        
        try {
            $customer = Mage::getModel('customer/customer')->load($customerId);
            $autoSwitchGroup = Mage::getModel('freento_groupautoswitch/group')->load($newGroupId, 'group_id');

            $autoSwitchGroup->switchGroup($customer);

            $customer = Mage::getModel('customer/customer')->load($customerId);
        } catch (Freento_GroupAutoSwitch_Exception_General $ex) {
            $this->assertEquals( $this->_getExpectations()->getData($testId . '/exception_message'), $ex->getMessage() );
        }
        $this->assertEquals( $this->_getExpectations()->getData($testId . '/new_group_id'), $customer->getGroupId() );
    }
    
    protected function _checkEmailSent($repeat)
    {
        $emailMock = $this->getModelMock('core/email_template', array('send'));
        $emailMock->expects($this->{$repeat}())
            ->method('send')
            ->will($this->returnCallback(function(){return true;}));
        $this->replaceByMock('model', 'core/email_template', $emailMock);
    }
    
    
    protected function _checkEmailTemplate($testId)
    {
        $emailMock = $this->getModelMock('core/email_template', array('loadDefault'));
        $emailMock->expects($this->once())
            ->method('loadDefault')
            ->will($this->returnCallback(array(Mage::getModel('core/email_template'), 'loadDefault')))
            ->with($this->equalTo($this->_getExpectations()->getData($testId . '/email_template')));
        $this->replaceByMock('model', 'core/email_template', $emailMock);
    }
}
