<?php

class Freento_Groupautoswitch_Model_Group extends Mage_Rule_Model_Rule
{
    const GROUP_AUTO_SWITCH_EMAIL_TEMPLATE = 'freento_groupautoswitch_group_auto_switch';
    const GROUP_DOWNGRADE_BEFORE_EMAIL_TEMPLATE = 'freento_groupautoswitch_group_downgrade_before';
    const GROUP_DOWNGRADE_EMAIL_TEMPLATE = 'freento_groupautoswitch_group_downgrade';
    
    protected function _construct()
    {
        parent::_construct();
        $this->_init('freento_groupautoswitch/group');
    }
    
    public function getConditionsInstance() 
    {
        return Mage::getModel('freento_groupautoswitch/rule_condition_combine');
    }
    
    public function downgradeBeforeEmail()
    {
        if ($this->getData('downgrade_after')) {
            $customerIdsWithOrders = $this->_getCustomerIdsWithOrders($this->getData('downgrade_after') - $this->getData('notify_customer_before_downgrade'));
            if ($this->getData('downgrade_group') > 0) {
                $downgradeCustomerIds = $this->_getCustomerIdsWithoutOrders($customerIdsWithOrders, $this->getData('downgrade_after') - $this->getData('notify_customer_before_downgrade'));
                if (count($downgradeCustomerIds)) {
                    $this->downgradeByCustomerIds($downgradeCustomerIds, $this->getData('downgrade_group'));
                    $this->_sendDowngradeEmail($downgradeCustomerIds);
                }
            }
        }
        
    }
    
    public function downgradeCheck()
    {
        if ($this->getData('downgrade_after')) {
            $customerIdsWithOrders = $this->_getCustomerIdsWithOrders($this->getData('downgrade_after'));
            if ($this->getData('downgrade_group') > 0) {
                $downgradeCustomerIds = $this->_getCustomerIdsWithoutOrders($customerIdsWithOrders, $this->getData('downgrade_after'));
                if (count($downgradeCustomerIds)) {
                    $this->_sendDowngradeBeforeEmail($downgradeCustomerIds);
                }
            }
        }
    }
    
    /**
     * Downgrade customers to some group
     * @param array $downgradeCustomerIds
     * @param int $downgradeGroupId
     */
    public function downgradeByCustomerIds($downgradeCustomerIds, $downgradeGroupId)
    {
         $query = 'UPDATE ' . Mage::getSingleton('core/resource')->getTableName('customer/entity') . ' as customer' .
                ' inner join ' . Mage::getSingleton('core/resource')->getTableName('customer/entity') . '_datetime' . ' as customer_datetime' .
                ' ON customer.entity_id = customer_datetime.entity_id' .
                ' set group_id = ' . $downgradeGroupId .
                ', customer_datetime.value = "' . Mage::getModel('core/date')->gmtDate() . '"' .
                ' where customer.entity_id in (' . implode(',', $downgradeCustomerIds) . ')';

        $groupModel = Mage::getModel('customer/group');
        $previousGroup = $groupModel->load($this->getData('group_id'))->getCustomerGroupCode();
        $newGroup = $groupModel->load($downgradeGroupId)->getCustomerGroupCode();
        $messages = array();
        foreach ($downgradeCustomerIds as $id) {
            $messages[] = Mage::helper('freento_groupautoswitch')->__('Downgrade. Customer ID: %s, previous group: %s, new group: %s', $id, $previousGroup, $newGroup);
        }
        $this->_log($messages);
        Mage::getSingleton('core/resource')->getConnection('core_write')->query($query);
    }
    
    /**
     * Returns customers with orders based on customers without orders ($customerWithOrdersIds) and
     * changed group earlier than N days ($lastDays)
     * @param array $customerWithOrdersIds
     * @param int $lastDays
     */
    protected function _getCustomerIdsWithoutOrders($customerWithOrdersIds, $lastDays)
    {
        $downgradeDatetime = $this->_getDateBeforeNow($lastDays);
        $customerGroupLastChangeDateAttribute = Mage::getModel('eav/entity_attribute')->loadByCode('customer', 'group_last_change_date');
        /* Get customers for downgrade (without sales from current group) ased on customers without orders */
        $query = 'SELECT customer.entity_id as customer_id from ' . Mage::getSingleton('core/resource')->getTableName('customer/entity') . ' as customer' .
                ' INNER JOIN ' . Mage::getSingleton('core/resource')->getTableName('customer/entity') . '_datetime' . ' as customer_datetime' .
                ' ON customer.entity_id = customer_datetime.entity_id' .
                ' where';
        if (count($customerWithOrdersIds)) {
            $query .= ' customer.entity_id not in (' . implode(',', $customerWithOrdersIds) . ') AND';
        }
        $query .= ' customer_datetime.value <= "' . $downgradeDatetime . '" AND';
        $query .= ' customer_datetime.attribute_id = ' . $customerGroupLastChangeDateAttribute->getId() . ' AND';
        $query .= ' group_id = ' . $this->getData('group_id');
        
        return Mage::getSingleton('core/resource')->getConnection('core_write')->fetchCol($query);
    }
    
    /**
     * Returns customers from current group that have orders for the last days ($lastDays)
     * @param int $lastDays
     */
    protected function _getCustomerIdsWithOrders($lastDays)
    {
        $downgradeDatetime = $this->_getDateBeforeNow($lastDays);
        $orderCollection = Mage::getModel('sales/order')
            ->getCollection()
            ->addAttributeToFilter('customer_group_id', array('eq' => $this->getData('group_id')));
        $orderCollection
            ->getSelect()
            ->where('created_at > ?', $downgradeDatetime)
            ->having('count(entity_id) > 0')
            ->group('customer_id');
        $customerIds = array();
        foreach ($orderCollection as $order) {
            if ($order->getCustomerId()) {
                $customerIds[] = $order->getCustomerId();
            }
        }
        return $customerIds;
    }
    
    protected function _getDateBeforeNow($days)
    {
        return date(Varien_Date::DATETIME_PHP_FORMAT, strtotime('-' . $days . ' days', strtotime(Mage::getModel('core/date')->gmtDate())));
    }
    
    public function switchGroup($customer)
    {
        if (!$this->getData('is_enabled')) {
            throw new Freento_GroupAutoSwitch_Exception_General('Auto Switch is disabled');
        }
        $groupModel = Mage::getModel('customer/group');
        $previousGroup = $groupModel->load($customer->getGroupId())->getCustomerGroupCode();
        $newGroup = $groupModel->load($this->getGroupId())->getCustomerGroupCode();
        $customer->setGroupId($this->getGroupId())->save();
        $this->_sendSwitchGroupEmail($customer, $newGroup);
        $message = Mage::helper('freento_groupautoswitch')->__('Switch Group. Customer ID: %s, email: %s, previous group: %s, new group: %s', $customer->getId(), $customer->getEmail(), $previousGroup, $newGroup);
        $this->_log(array($message));
    }
    
    protected function _sendSwitchGroupEmail($customer, $newGroup)
    {
        $result = false;
        if ($customer->getId()) {
            $templateId = $this->getData('notify_on_group_change_template');
            if(!$templateId) {
                $templateId = self::GROUP_AUTO_SWITCH_EMAIL_TEMPLATE;
            }

            $customerName   = $customer->getName();
            $emailTemplate  = Mage::getModel('core/email_template')->loadDefault($templateId);
            $emailTemplateVariables = array(
                'customer_name' => $customerName,
                'old_group' => Mage::getModel('customer/group')->load($customer->getGroupId())->getCustomerGroupCode(),
                'new_group' => $newGroup,
                'store_name' => Mage::getStoreConfig('general/store_information/name'),
            );

            $emailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_general/name'));
            $emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_general/email')); 

            $result = $emailTemplate->send($customer->getEmail(), $customerName, $emailTemplateVariables);
        }
        return $result;
    }
    
    public function _sendDowngradeEmail(array $customerIds)
    {
        if(!is_array($customerIds)) {
            return false;
        }
        
        $customerCollection = Mage::getResourceModel('customer/customer_collection')
            ->addNameToSelect();
        $customerCollection->addFieldToFilter('entity_id', array('in' => $customerIds));
        
        if($templateId = $this->getData('downgrade_template')) {
            $emailTemplate = Mage::getModel('core/email_template')->load($templateId);
        } else {
            $emailTemplate = Mage::getModel('core/email_template')->loadDefault(self::GROUP_DOWNGRADE_EMAIL_TEMPLATE);
        }
        $groupModel = Mage::getModel('customer/group');
        
        foreach ($customerCollection as $customer) {
            $customerName   = $customer->getName();
            $newGroup = $groupModel->load($customer->getGroupId())->getCustomerGroupCode();
            
            $emailTemplateVariables = array(
                'customer_name' => $customerName,
                'store_name' => Mage::getStoreConfig('general/store_information/name'),
                'new_group' => $newGroup,
            );

            $emailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_general/name'));
            $emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_general/email')); 

            $emailTemplate->send($customer->getEmail(), $customerName, $emailTemplateVariables);
        }
        return true;
    }
    
    protected function _sendDowngradeBeforeEmail(array $customerIds)
    {
        if(!is_array($customerIds)) {
            return false;
        }
        
        $customerCollection = Mage::getResourceModel('customer/customer_collection')
            ->addNameToSelect();
        $customerCollection->addFieldToFilter('entity_id', array('in' => $customerIds));
        
        if($templateId = $this->getData('notify_customer_before_downgrade_template')) {
            $emailTemplate = Mage::getModel('core/email_template')->load($templateId);
        } else {
            $emailTemplate = Mage::getModel('core/email_template')->loadDefault(self::GROUP_DOWNGRADE_BEFORE_EMAIL_TEMPLATE);
        }
        
        foreach ($customerCollection as $customer) {
            $customerName   = $customer->getName();
            
            $emailTemplateVariables = array(
                'customer_name' => $customerName,
                'store_name' => Mage::getStoreConfig('general/store_information/name'),
                'days_to_downgrade' => $this->getData('downgrade_after'),
            );

            $emailTemplate->setSenderName(Mage::getStoreConfig('trans_email/ident_general/name'));
            $emailTemplate->setSenderEmail(Mage::getStoreConfig('trans_email/ident_general/email')); 

            $emailTemplate->send($customer->getEmail(), $customerName, $emailTemplateVariables);
        }
        return true;
    }
    
    public function validate(Varien_Object $object)
    {
        $group = Mage::getModel('customer/group')->load($this->getGroupId());
        $result = parent::validate($object);
        $message = Mage::helper('freento_groupautoswitch')->__(
            'Validation. Customer: id %s, email %s, group: %s switch. Validation result: %s',
            $object->getCustomer()->getId() ? $object->getCustomer()->getId() : 0,
            $object->getCustomer()->getEmail(),
            $group->getCustomerGroupCode(),
            $result ? 'true' : 'false'
        );
        $this->_log(array($message));
        return $result;
    }
    
    protected function _log(array $messages)
    {
        $writeConnection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $table = Mage::getSingleton('core/resource')->getTableName('freento_groupautoswitch/log');
        $query = 'INSERT INTO `' . $table . '` (`details`, `created_at`) VALUES ' ;
        
        $now = Mage::getSingleton('core/date')->gmtDate();
        $rows = array();
        foreach($messages as $message) {
            $rows[] = "('$message', '$now')";
        }

        if(!empty($rows)) {
            $query .= implode(', ', $rows);
            $writeConnection->query($query);
        }
    }
    
}
