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
    register_post_type(
        'activity',
         array(
            'labels' => array( 'name' => __( 'Мероприятия' ),
            'singular_name' => __( 'Мероприятия' ) ),
            'supports'      => array( 'title', 'editor', 'thumbnail'),
            'has_archive' => true,
            'show_in_rest' => true,
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'public' => true, 
        ) 
    );  
}

//Корзина вверху
add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
function woocommerce_header_add_to_cart_fragment( $fragments )
{
    global $woocommerce;
    ob_start();
    my_wc_cart_count();
    $fragments['.main-header-cart'] = ob_get_clean();
    return $fragments;
}
function my_wc_cart_count() {
    global $woocommerce; ?>
    <a href="<?= get_site_url(); ?>/cart" class="main-header-cart">
        <span class="count-prod"><?php echo WC()->cart->get_total(); ?></span>
        <svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0)" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.5 18.333a.833.833 0 100-1.666.833.833 0 000 1.666zM16.667 18.333a.833.833 0 100-1.666.833.833 0 000 1.666zM.833.833h3.334L6.4 11.992a1.666 1.666 0 001.667 1.341h8.1a1.666 1.666 0 001.666-1.341L19.167 5H5"/></g><defs><clipPath id="clip0"><path d="M0 0h20v20H0V0z" fill="#fff"/></clipPath></defs></svg>
    </a>
<?php
}
add_action('header_action', 'my_wc_cart_count');