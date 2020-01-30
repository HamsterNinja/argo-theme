<?
if (function_exists('acf_add_local_field_group')):
    acf_add_local_field_group(array(
        'key' => 'group_5e2fe248d81d4',
        'title' => 'Продукт',
        'fields' => array(
            array(
                'key' => 'field_5e2fe25018c16',
                'label' => 'Состав',
                'name' => 'composition',
                'type' => 'wysiwyg',
            ) ,
        ) ,
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'product',
                ) ,
            ) ,
        ) ,
    ));
    acf_add_local_field_group(array(
        'key' => 'group_5e2fe248d81d23',
        'title' => 'Галерея',
        'fields' => array(
            array(
                'key' => 'field_5e2fe25018c12324',
                'label' => 'Галерея',
                'name' => 'gallery',
                'type' => 'gallery',
            ) ,
        ) ,
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'gallery',
                ) ,
            ) ,
        ) ,
    ));

    acf_add_local_field_group(array(
        'key' => 'group_5d72670b14d3b',
        'title' => 'Основные настройки',
        'fields' => array(
            array(
                'key' => 'field_5d653c6e46d8b',
                'label' => 'email',
                'name' => 'email',
                'type' => 'text',
            ) ,
            array(
                'key' => 'field_5d653c7a46d8c_1',
                'label' => 'Телефон 1',
                'name' => 'phone_1',
                'type' => 'text',
            ),
            array(
                'key' => 'field_5d653c7a46d8c_2',
                'label' => 'Телефон 2',
                'name' => 'phone_2',
                'type' => 'text',
            ) ,
            array(
                'key' => 'field_5d653c9646d8d',
                'label' => 'vk',
                'name' => 'vk',
                'type' => 'text',
            ) ,
            array(
                'key' => 'field_5c99f94f45926',
                'label' => 'facebook',
                'name' => 'facebook',
                'type' => 'text'
            ) ,
            array(
                'key' => 'field_5c99f95645927',
                'label' => 'ok',
                'name' => 'ok',
                'type' => 'text'
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'options'
                )
            )
        ) ,
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => ''
    ));

endif;