<?php

return
    array(
        'customer_entity' => array(
            array(
                'entity_id' => '1',
                'entity_type_id' => '1',
                'attribute_set_id' => '0',
                'website_id' => '1',
                'email' => 'test@test.com',
                'group_id' => '10',
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
        ),
        'freento_groupautoswitch_group' => array(
            array(
                'id' => '1',
                'is_enabled' => '1',
                'group_id' => '10',
                'priority' => '0',
                'conditions_serialized' => 'a:6:{s:4:"type";s:41:"rc_groupautoswitch/rule_condition_combine";s:9:"attribute";N;s:8:"operator";N;s:5:"value";s:1:"1";s:18:"is_value_processed";N;s:10:"aggregator";s:3:"all";}',
                'stop_further_rules' => '0',
                'downgrade_template' => '',
                'notify_on_group_change_template' => '',
                'text_for_customer' => '',
                'downgrade_after' => '10',
                'downgrade_group' => '1',
                'notify_customer_before_downgrade' => '1',
                'notify_customer_before_downgrade_template' => ''
            ),
        ),
        'eav_attribute' => array(
            array(
                'attribute_id' => '961',
                'entity_type_id' => '1',
                'attribute_code' => 'group_last_change_date',
                'attribute_model' => NULL,
                'backend_model' => 'eav/entity_attribute_backend_datetime',
                'backend_type' => 'datetime',
                'backend_table' => NULL,
                'frontend_model' => 'eav/entity_attribute_frontend_datetime',
                'frontend_input' => 'date',
                'frontend_label' => 'Customer Group Last Change Date',
                'frontend_class' => NULL,
                'source_model' => NULL,
                'is_required' => '0',
                'is_user_defined' => '0',
                'default_value' => NULL,
                'is_unique' => '0',
                'note' => NULL
            )
        ),
        'customer_eav_attribute' => array(
            array(
                'attribute_id' => '961',
                'is_visible' => '1',
                'input_filter' => NULL,
                'multiline_count' => '1',
                'validate_rules' => NULL,
                'is_system' => '0',
                'sort_order' => '0',
                'data_model' => NULL
            )
        ),
        'customer_entity_datetime' => array(
            array(
                'value_id' => '1',
                'entity_type_id' => '1',
                'attribute_id' => '961',
                'entity_id' => '1',
                'value' => '2015-04-04 07:25:10'
            )
        )
    );