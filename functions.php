<?php
include_once(get_template_directory() .'/include/models.php');
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );


if ( class_exists( 'Timber' ) ){
    Timber::$cache = false;
}

if ( class_exists( 'Timber' ) ){
    include_once(get_template_directory() .'/include/Timber/Integrations/WooCommerce/WooCommerce.php');
    include_once(get_template_directory() .'/include/Timber/Integrations/WooCommerce/ProductsIterator.php');
    include_once(get_template_directory() .'/include/Timber/Integrations/WooCommerce/Product.php');
    if ( class_exists( 'WooCommerce' ) ) {
        Timber\Integrations\WooCommerce\WooCommerce::init();
    }
}

add_action( 'after_setup_theme', function() {
    add_theme_support( 'woocommerce' );
} );


add_filter('the_generator', '__return_empty_string');

add_image_size( 'blog_image', 438, 257, true ); 
add_image_size( 'catalog_image', 360, 370, true ); 
add_image_size( 'catalog_image_x2', 720, 405, true ); 

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' ); 
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' ); 
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
add_filter( 'tiny_mce_plugins', 'disable_wp_emojis_in_tinymce' );
function disable_wp_emojis_in_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}
function true_remove_default_widget() {
	unregister_widget('WP_Widget_Archives'); 
	unregister_widget('WP_Widget_Calendar');
	unregister_widget('WP_Widget_Categories'); 
	unregister_widget('WP_Widget_Meta'); 
	unregister_widget('WP_Widget_Pages');
	unregister_widget('WP_Widget_Recent_Comments'); 
	unregister_widget('WP_Widget_Recent_Posts'); 
	unregister_widget('WP_Widget_RSS'); 
	unregister_widget('WP_Widget_Search'); 
	unregister_widget('WP_Widget_Tag_Cloud'); 
	unregister_widget('WP_Widget_Text'); 
	unregister_widget('WP_Nav_Menu_Widget'); 
}
 
add_action( 'widgets_init', 'true_remove_default_widget', 20 );

//Disable gutenberg style in Front
function wps_deregister_styles() {
    wp_dequeue_style( 'wp-block-library' );
}
add_action( 'wp_print_styles', 'wps_deregister_styles', 100 );

//remove type js and css for validator
add_action('wp_loaded', 'prefix_output_buffer_start');
function prefix_output_buffer_start() { 
    ob_start("prefix_output_callback"); 
}
add_action('shutdown', 'prefix_output_buffer_end');
function prefix_output_buffer_end() { 
    ob_end_flush(); 
}
function prefix_output_callback($buffer) {
    return preg_replace( "%[ ]type=[\'\"]text\/(javascript|css)[\'\"]%", '', $buffer );
}

register_nav_menus(array(
    'menu_header' => 'Верхнее меню',
    'menu_footer' => 'Нижние меню',
));

function add_async_forscript($url)
{
    if (strpos($url, '#asyncload')===false)
        return $url;
    else if (is_admin())
        return str_replace('#asyncload', '', $url);
    else
        return str_replace('#asyncload', '', $url)."' defer='defer"; 
}

add_filter('clean_url', 'add_async_forscript', 11, 1);
function time_enqueuer($my_handle, $relpath, $type='script', $async='false', $media="all",  $my_deps=array()) {
    if($async == 'true'){
        $uri = get_theme_file_uri($relpath.'#asyncload');
    }
    else{
        $uri = get_theme_file_uri($relpath);
    }
    $vsn = filemtime(get_theme_file_path($relpath));
    if($type == 'script') wp_enqueue_script($my_handle, $uri, $my_deps, $vsn);
    else if($type == 'style') wp_enqueue_style($my_handle, $uri, $my_deps, $vsn, $media);      
}

add_action('wp_footer', 'add_scripts');
function add_scripts() {
    time_enqueuer('jquerylatest', '/assets/js/vendors/jquery-3.4.1.min.js', 'script', true);
    time_enqueuer('slick', '/assets/js/vendors/slick.js', 'script', true);

    time_enqueuer('mansoryjs', '/assets/js/vendors/masonry.pkgd.min.js', 'script', true);
    time_enqueuer('fancybox', '/assets/js/vendors/jquery.fancybox.min.js', 'script', true);
    
    time_enqueuer('app', '/assets/js/main.bundle.js', 'script', true);
    if (is_page('contacts') || is_home() ) {
        wp_enqueue_script('yandex-map-api', 'https://api-maps.yandex.ru/2.1/?lang=ru_RU&load=Map,Placemark#asyncload'); 
    }

    $queried_object = get_queried_object();
    if ($queried_object) {
        $term_id = $queried_object->term_id;
        $term = get_term( $term_id, 'product_cat' );
        $category_slug = $term->slug;
    }
    if($_GET && $category_slug == null){
        if ($_GET['product-cat']) {
            $category_slug = $_GET['product-cat'];
        }
    }

    $user_id = get_current_user_id();
    if ($user_id) {
        $user = get_userdata(get_current_user_id());
    }

    if ($user) {
        $user_email = $user->get('user_email');
        $user_email = !empty($user_email) ? $user_email : '';

        $user_first_name = $user->get('first_name');
        $user_first_name = !empty($user_first_name) ? $user_first_name : '';

        $user_last_name  = $user->get('last_name');
        $user_last_name = !empty($user_last_name) ? $user_last_name : '';

        $user_city = get_field('shipping_city', 'user_'.$user_id);
        $user_city = !empty($user_city) ? $user_city : '';

        $user_phone = get_field('billing_mobile_phone', 'user_'.$user_id);
        $user_phone = !empty($user_phone) ? $user_phone : '';

        $user_street = get_field('shipping_street', 'user_'.$user_id);
        $user_street = !empty($user_street) ? $user_street : '';

        $user_house = get_field('shipping_house', 'user_'.$user_id);
        $user_house = !empty($user_house) ? $user_house : '';

        $user_apartment = get_field('shipping_apartment', 'user_'.$user_id);
        $user_apartment = !empty($user_apartment) ? $user_apartment : '';

        $user_intercom = get_field('shipping_intercom', 'user_'.$user_id);
        $user_intercom = !empty($user_intercom) ? $user_intercom : '';

        $user_floor = get_field('shipping_floor', 'user_'.$user_id);
        $user_floor = !empty($user_floor) ? $user_floor : '';

        $user_porch = get_field('shipping_porch', 'user_'.$user_id);
        $user_porch = !empty($user_porch) ? $user_porch : '';
        
        $currentUser = new customUser();
        $currentUser->setUserID($user_id)
        ->setEmail($user_email)
        ->setFirstName($user_first_name)
        ->setLastName($user_last_name)
        ->setCity($user_city)
        ->setStreet($user_street)
        ->setHouse($user_house)
        ->setApartment($user_apartment)
        ->setIntercom($user_intercom)
        ->setFloor($user_floor)
        ->setPorch($user_porch)
        ->setPhone($user_phone);
    }

    if (is_product()) {
        $post_params = Timber::get_post();
        $product_params = wc_get_product( $post_params->ID );
        $regular_price = $product_params->get_regular_price();
        $sale_price = $product_params->get_sale_price();
    }
    else{
        $regular_price = 0;
        $sale_price = 0;
    }

    wp_localize_script( 'app', 'SITEDATA', array(
        'url' => get_site_url(),
        'themepath' => get_template_directory_uri(),
        'ajax_url' => admin_url('admin-ajax.php'),
        'product_id' => get_the_ID(),
        'is_home' => is_home() ? 'true' : 'false',
        'is_product' => is_product() ? 'true' : 'false',
        'is_filter' => is_page('filter') ? 'true' : 'false',
        'is_cart' => is_cart() ? 'true' : 'false',
        'is_search' => is_search() ? 'true' : 'false',
        'search_query' => get_search_query() ? get_search_query() : '',
        'category_slug' => $category_slug,
        'is_shop' => is_shop() ? 'true' : 'false',
        'current_user_id' => get_current_user_id(),
        'user_url' => $user_url,
        'regular_price' => $regular_price,
        'sale_price' => $sale_price ,
        'paged' => $paged ,
        'nonce_like' => $nonce_like ,
        'cart_subtotal' => WC()->cart->subtotal,
        'ajax_noncy_nonce' =>  wp_create_nonce( 'noncy_nonce' ),
        'user_data' => $currentUser
    ));
}

//wp-embed.min.js remove
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );

//remove jquery-migrate
function dequeue_jquery_migrate( $scripts ) {
	if ( ! is_admin() && ! empty( $scripts->registered['jquery'] ) ) {
		$jquery_dependencies = $scripts->registered['jquery']->deps;
		$scripts->registered['jquery']->deps = array_diff( $jquery_dependencies, array( 'jquery-migrate' ) );
	}
}
add_action( 'wp_default_scripts', 'dequeue_jquery_migrate' );

function add_styles() {
        if(is_admin()) return false; 
        time_enqueuer('main', '/assets/css/main.css', 'style', false, 'all');   
}
add_action('wp_print_styles', 'add_styles');

if ( class_exists( 'Timber' ) ){
    Timber::$dirname = array('templates', 'views');
    class StarterSite extends TimberSite {
        function __construct() {
            add_theme_support( 'post-formats' );
            add_theme_support( 'post-thumbnails' );
            add_theme_support( 'woocommerce' );
            add_theme_support( 'menus' );
            add_filter( 'timber_context', array( $this, 'add_to_context' ) );
            add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
            parent::__construct();
        }
        
        function add_to_context( $context ) {
            $context['site'] = $this;
            $context['menu_header'] = new TimberMenu('menu_header');  
            $context['menu_footer'] = new TimberMenu('menu_footer');  

            $context['wp_logout_url'] = wp_logout_url(get_permalink());  

            $user_id = get_current_user_id();
            $context['user_id'] = $user_id;

            // TODO: вынести в функцию данные пользователя
            $user_id = get_current_user_id();
            if ($user_id) {
                $user = get_userdata(get_current_user_id());
            }
            else{
                $user = false;
            }

            $context['facebook'] = get_field('facebook', 'options');
            $context['vk'] = get_field('vk', 'options');
            $context['ok'] = get_field('ok', 'options');
            $context['email'] = get_field('email', 'options');
            $context['phone_1'] = get_field('phone_1', 'options');
            $context['phone_2'] = get_field('phone_2', 'options');
            $context['map_frame'] = get_field('map_frame', 'options');
            $args_activities = [
                'post_type' => 'activity',
                'posts_per_page' => 3,
            ];
            $context['activities'] = Timber::get_posts( $args_activities );

            $context['worktime_delivery'] = get_field('worktime_delivery', 'options');
            $context['worktime_restaurant'] = get_field('worktime_restaurant', 'options');
            $context['worktime_restaurant_title'] = get_field('worktime_restaurant_title', 'options');
            $context['worktime_delivery_title'] = get_field('worktime_delivery_title', 'options');
            $context['address'] = get_field('address', 'options');

            $context['footer_title'] = get_field('footer_title', 'options');
                
            return $context;
        }
    }
    new StarterSite();

    function timber_set_product( $post ) {
        global $product;
        
        if ( is_woocommerce() || is_home() || is_page('filter') ) {
            $product = wc_get_product( $post->ID );
        }
    }
}

include_once(get_template_directory() .'/include/acf-fields.php');
include_once(get_template_directory() .'/include/rest-api.php');
include_once(get_template_directory() .'/include/create_user.php');
include_once(get_template_directory() .'/include/theme-settings.php');