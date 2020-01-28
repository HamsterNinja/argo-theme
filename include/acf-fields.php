<?
if( function_exists('acf_add_local_field_group') ):
    acf_add_local_field_group(array(
        'key' => 'group_5e2fe248d81d4',
        'title' => 'Продукт',
        'fields' => array(
            array(
                'key' => 'field_5e2fe25018c16',
                'label' => 'Состав',
                'name' => 'composition',
                'type' => 'wysiwyg',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'product',
                ),
            ),
        ),
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
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'gallery',
                ),
            ),
        ),
    )); 
endif;