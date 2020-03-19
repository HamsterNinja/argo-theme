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
            array(
                'key' => 'field_5c99f95645927_map_frame',
                'label' => 'map_frame',
                'name' => 'map_frame',
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

    acf_add_local_field_group(array(
        'key' => 'group_5e37e3e54008d',
        'title' => 'Бронирование',
        'fields' => array(
            array(
                'key' => 'field_5e37e4fd07275',
                'label' => 'Имя',
                'name' => 'name',
                'type' => 'text',
            ),
            array(
                'key' => 'field_5e37e51307276',
                'label' => 'Телефон',
                'name' => 'phone',
                'type' => 'text',
                'instructions' => '',
            ),
            array(
                'key' => 'field_5e37e54807277',
                'label' => 'Пожелания',
                'name' => 'description',
                'type' => 'text',
            ),
            array(
                'key' => 'field_5e37e3f807271',
                'label' => 'Зал',
                'name' => 'hall',
                'type' => 'number',
            ),
            array(
                'key' => 'field_5e37e41007272',
                'label' => 'Стол',
                'name' => 'table',
                'type' => 'number',
            ),
            array(
                'key' => 'field_5e37e410072_guests',
                'label' => 'Гости',
                'name' => 'guests',
                'type' => 'number',
            ),
            array(
                'key' => 'field_5e37e42607273',
                'label' => 'Время',
                'name' => 'time',
                'type' => 'time_picker',
                'display_format' => 'H:i:s',
                'return_format' => 'H:i:s',
            ),
            array(
                'key' => 'field_5e37e47707274',
                'label' => 'Дата',
                'name' => 'date',
                'type' => 'date_picker',
                'display_format' => 'd/m/Y',
                'return_format' => 'd/m/Y',
                'first_day' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'record',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    acf_add_local_field_group(array(
        'key' => 'group_5e6baee236d1b',
        'title' => 'Основное меню',
        'fields' => array(
            array(
                'key' => 'field_5e6baefb948a9',
                'label' => 'Подменю',
                'name' => 'submenu_main',
                'type' => 'repeater',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5e6baf1b948aa',
                        'label' => 'Название',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_5e6baf28948ab',
                        'label' => 'Изображения',
                        'name' => 'images',
                        'type' => 'gallery',
                        'return_format' => 'url',
                        'preview_size' => 'medium',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'options',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    acf_add_local_field_group(array(
        'key' => 'group_5e6baee236d1b_banket',
        'title' => 'Банкетное меню',
        'fields' => array(
            array(
                'key' => 'field_5e6baefb948a9_banket',
                'label' => 'Подменю',
                'name' => 'submenu_banket',
                'type' => 'repeater',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5e6baf1b948aa_banket',
                        'label' => 'Название',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_5e6baf28948ab_banket',
                        'label' => 'Изображения',
                        'name' => 'images',
                        'type' => 'gallery',
                        'return_format' => 'url',
                        'preview_size' => 'medium',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'options',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    acf_add_local_field_group(array(
        'key' => 'group_5e6baee236d1b_pomin',
        'title' => 'Поминальное меню',
        'fields' => array(
            array(
                'key' => 'field_5e6baefb948a9_pomin',
                'label' => 'Подменю',
                'name' => 'submenu_pomin',
                'type' => 'repeater',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5e6baf1b948aa_pomin',
                        'label' => 'Название',
                        'name' => 'name',
                        'type' => 'text',
                    ),
                    array(
                        'key' => 'field_5e6baf28948ab_pomin',
                        'label' => 'Изображения',
                        'name' => 'images',
                        'type' => 'gallery',
                        'return_format' => 'url',
                        'preview_size' => 'medium',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'options',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));

    acf_add_local_field_group(array(
        'key' => 'group_5e709b929a1e8',
        'title' => 'О ресторане',
        'fields' => array(
            array(
                'key' => 'field_5d653c7a46d8c_block_1_title',
                'label' => 'Блок 1 заголовок',
                'name' => 'block_1_title',
                'type' => 'text',
            ),
            array(
                'key' => 'field_5e709b9f15245',
                'label' => 'Блок 1 текст',
                'name' => 'block_1_text',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_5e709bc915246',
                'label' => 'Блок 1 изображение',
                'name' => 'block_1_images',
                'type' => 'image',
                'return_format' => 'url',
                'preview_size' => 'medium',
                'library' => 'all',
            ),
            array(
                'key' => 'field_5d653c7a46d8c_block_2_title',
                'label' => 'Блок 2 заголовок',
                'name' => 'block_2_title',
                'type' => 'text',
            ),
            array(
                'key' => 'field_5e709be815247',
                'label' => 'Блок 2 текст',
                'name' => 'block_2_text',
                'type' => 'textarea',
            ),
            array(
                'key' => 'field_5e709bfc15248',
                'label' => 'Блок 2 изображение',
                'name' => 'block_2_images',
                'type' => 'image',
                'return_format' => 'url',
                'preview_size' => 'medium',
                'library' => 'all',
            ),
            array(
                'key' => 'field_5e709c5791a00',
                'label' => 'Наши партнеры',
                'name' => 'our_partners',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'table',
                'button_label' => '',
                'sub_fields' => array(
                    array(
                        'key' => 'field_5e709c7f91a01',
                        'label' => 'Лого',
                        'name' => 'logo',
                        'type' => 'image',
                        'return_format' => 'url',
                        'preview_size' => 'medium',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page',
                    'operator' => '==',
                    'value' => '34',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
endif;