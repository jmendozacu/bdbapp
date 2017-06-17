<?php

return
    array('tables' => array(
        'customer_entity' => array(
            array(
                'entity_id' => '1',
                'entity_type_id' => '1',
                'attribute_set_id' => '0',
                'website_id' => '1',
                'email' => 'test@test.com',
                'group_id' => '1',
                'increment_id' => NULL,
                'store_id' => '1',
                'created_at' => '2015-04-02 20:54:56',
                'updated_at' => '2015-04-02 20:54:56',
                'is_active' => '1',
                'disable_auto_group_change' => '0'
            )
        ),
        'customer_group' => array(
            array(
                'customer_group_id' => '1',
                'customer_group_code' => 'General',
                'tax_class_id' => '3'
            ),
            array(
                'customer_group_id' => '10',
                'customer_group_code' => 'Level 1',
                'tax_class_id' => '3'
            ),
            array(
                'customer_group_id' => '11',
                'customer_group_code' => 'Level 10',
                'tax_class_id' => '3'
            )
        ),
        'freento_groupautoswitch_group' => array(
            array(
                'id' => '1',
                'is_enabled' => 1,
                'group_id' => '10',
                'priority' => '0',
                'conditions_serialized' => 'a:7:{s:4:"type";s:41:"freento_groupautoswitch/rule_condition_combine";s:9:"attribute";N;s:8:"operator";N;s:5:"value";s:1:"1";s:18:"is_value_processed";N;s:10:"aggregator";s:3:"all";s:10:"conditions";a:2:{i:0;a:5:{s:4:"type";s:58:"freento_groupautoswitch/rule_condition_customer_attribute_group";s:9:"attribute";s:5:"group";s:8:"operator";s:2:"==";s:5:"value";a:2:{i:0;s:1:"1";i:1;s:1:"2";}s:18:"is_value_processed";b:0;}i:1;a:5:{s:4:"type";s:66:"freento_groupautoswitch/rule_condition_customer_statistic_lifetimeSales";s:9:"attribute";s:13:"lifetimeSales";s:8:"operator";s:2:">=";s:5:"value";s:2:"50";s:18:"is_value_processed";b:0;}}}',
                'stop_further_rules' => '0',
                'downgrade_template' => '',
                'notify_on_group_change_template' => '',
                'text_for_customer' => '',
                'downgrade_after' => '0',
                'downgrade_group' => '-1',
                'notify_customer_before_downgrade' => '0',
                'notify_customer_before_downgrade_template' => ''
            ),
            array(
                'id' => '2',
                'is_enabled' => 0,
                'group_id' => '11',
                'priority' => '0',
                'conditions_serialized' => 'a:7:{s:4:"type";s:41:"freento_groupautoswitch/rule_condition_combine";s:9:"attribute";N;s:8:"operator";N;s:5:"value";s:1:"1";s:18:"is_value_processed";N;s:10:"aggregator";s:3:"all";s:10:"conditions";a:2:{i:0;a:5:{s:4:"type";s:58:"freento_groupautoswitch/rule_condition_customer_attribute_group";s:9:"attribute";s:5:"group";s:8:"operator";s:2:"==";s:5:"value";a:2:{i:0;s:1:"1";i:1;s:1:"2";}s:18:"is_value_processed";b:0;}i:1;a:5:{s:4:"type";s:66:"freento_groupautoswitch/rule_condition_customer_statistic_lifetimeSales";s:9:"attribute";s:13:"lifetimeSales";s:8:"operator";s:2:">=";s:5:"value";s:2:"50";s:18:"is_value_processed";b:0;}}}',
                'stop_further_rules' => '0',
                'downgrade_template' => '',
                'notify_on_group_change_template' => '',
                'text_for_customer' => '',
                'downgrade_after' => '0',
                'downgrade_group' => '-1',
                'notify_customer_before_downgrade' => '0',
                'notify_customer_before_downgrade_template' => ''
            ),
        )
    ));