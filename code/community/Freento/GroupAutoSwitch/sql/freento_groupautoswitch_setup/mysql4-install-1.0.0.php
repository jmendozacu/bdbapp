<?php

$this->startSetup();

$this->run("

CREATE TABLE IF NOT EXISTS {$this->getTable('freento_groupautoswitch/group')} (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `is_enabled` tinyint(4) NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `priority` int(10) unsigned NOT NULL,
  `conditions_serialized` text NOT NULL,
  `stop_further_rules` tinyint(4) NOT NULL,
  `downgrade_template` varchar(255) NOT NULL,
  `notify_on_group_change_template` varchar(255) NOT NULL,
  `text_for_customer` text NOT NULL,
  `downgrade_after` int(11) NOT NULL,
  `downgrade_group` int(11) NOT NULL,
  `notify_customer_before_downgrade` int(11) NOT NULL,
  `notify_customer_before_downgrade_template` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$this->getTable('freento_groupautoswitch/log')} (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `details` text,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$entityTypeId     = $setup->getEntityTypeId('customer');
$attributeSetId   = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$setup->addAttribute('customer', 'group_last_change_date', array(
    'type'           => 'datetime',
    'input'          => 'date',
    'validate_rules' => 'a:1:{s:16:"input_validation";s:4:"date";}',
    'visible'        => true,
    'required'       => false,
    'label'         => 'Customer Group Last Change Date',
));

$setup->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'group_last_change_date',
    '1000'
);

$oAttribute = Mage::getSingleton('eav/config')->getAttribute('customer', 'group_last_change_date');
$oAttribute->setData('used_in_forms', array('adminhtml_customer'));
$oAttribute->save();

$this->endSetup();