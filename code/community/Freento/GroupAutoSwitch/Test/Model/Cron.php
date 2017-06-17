<?php

class Freento_GroupAutoSwitch_Test_Model_Cron extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Check cron forceFill() method
     *
     * @test
     * @loadFixture
     * @loadExpectation
     * @dataProvider dataProvider
     */
    public function forceFill($testId, $customerId)
    {
        /* @var $autoSwitchCron Freento_Groupautoswitch_Model_Cron */
        $autoSwitchCron = Mage::getModel('freento_groupautoswitch/cron');
        $autoSwitchCron->forceFill();
        
        $customer = Mage::getModel('customer/customer')->load($customerId);
        $this->assertEquals( $this->_getExpectations()->getData($testId . '/new_group_id'), $customer->getGroupId() );
        $this->assertEquals( $this->_getExpectations()->getData($testId . '/last_id'), $customer->getId() );
    }
    
    /**
     * Check cron downgradeCheck() method
     *
     * @test
     * @loadFixture
     * @loadExpectation
     * @dataProvider dataProvider
     */
    public function downgradeCheck($testId, $customerId)
    {
        
        /* @var $autoSwitchCron Freento_Groupautoswitch_Model_Cron */
        $autoSwitchCron = Mage::getModel('freento_groupautoswitch/cron');
        $autoSwitchCron->downgradeCheck();
        
        $customer = Mage::getModel('customer/customer')->load($customerId);
        $this->assertEquals( $this->_getExpectations()->getData($testId . '/new_group_id'), $customer->getGroupId() );
    }
}