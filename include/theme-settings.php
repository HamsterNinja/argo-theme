<? 
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title'    => 'Основные настройки',
        'menu_title'    => 'Основные настройки',
        'menu_slug'     => 'options',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
};

add_action( 'init', 'create_my_post_types' );
function create_my_post_types() {
    register_post_type(
        'gallery',
         array(
            'labels' => array( 'name' => __( 'Фото' ),
            'singular_name' => __( 'Фото' ) ),
            'supports'      => array( 'title', 'thumbnail'),
            'has_archive' => true,
            'show_in_rest' => true,
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'public' => true, 
        ) 
    );   
}