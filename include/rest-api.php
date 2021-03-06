<?php

function add_one_product() {        
    ob_start();
    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity          = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
    $product_status    = get_post_status( $product_id );
    if ( $passed_validation && WC()->cart->add_to_cart( $product_id, $quantity ) && 'publish' === $product_status ) {
        do_action( 'woocommerce_ajax_added_to_cart', $product_id );
        wc_add_to_cart_message( $product_id );
        $fragments = WC_AJAX::get_refreshed_fragments();
        wp_send_json_success($fragments);
    } else {
        $data = array(
            'error'       => true,
            'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id )
        );
        wp_send_json( $data );
    }
    die();
}
add_action('wp_ajax_add_one_product', 'add_one_product');
add_action('wp_ajax_nopriv_add_one_product', 'add_one_product');


function ajax_login_init(){
    add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );
}
if (!is_user_logged_in()) {
    add_action('init', 'ajax_login_init');
}

function phoneFormat($number) {
    $number = '+7 ('.substr($number, 0, 3).') '.substr($number, 3, 3).'-'.substr($number, 6, 2).'-'.substr($number, 8, 2);
	return $number;
}

function phoneClear($number) {
    $number = preg_replace("/[^\d]/","",$number);
    if(strlen($number) > 10) {
        $number = substr($number, -10);
    }
    return $number;
}

function ajax_login(){
    $info = [];
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;

    $matchingUsers = get_users(array(
        'meta_key'     => 'billing_mobile_phone',
        'meta_value'   => phoneFormat(phoneClear($_POST['username'])),
        'meta_compare' => 'LIKE'
    ));

    if(is_array($matchingUsers) && !empty($matchingUsers)) {
        $info['user_login'] = $matchingUsers[0]->user_login;
    }
    
    $user_signon = wp_signon( $info, false );
    if ( is_wp_error($user_signon) ){
        echo json_encode(
            [
                'loggedin' => false,
                'user_login' => $info['user_login'],
                'message' => __('Неверное имя пользователя или пароль'),
                'phone' => phoneFormat(phoneClear($_POST['username'])),
                'password' => $_POST['password']
            ]
        );
    } else {
        echo json_encode(
            [
                'loggedin' => true,
                'user_login' => $info['user_login'],
                'message' => __('Авторизация успешна'),
                'phone' => phoneFormat(phoneClear($_POST['username'])),
                'password' => $_POST['password']
            ]
        );
    }
    die();
}

function changePassword(WP_REST_Request $request){
    $user_id = sanitize_text_field($_POST['user_id']);
    $old_password = sanitize_text_field($_POST['old_password']);
    $new_password = sanitize_text_field($_POST['new_password']);

    if ($user_id && $old_password && $new_password) {
        $data = ['status' => 'success'];
        wp_set_password( $new_password, $user_id );
    }
    else {
        $data = ['status' => 'fail'];
    }
    wp_send_json_success($data);
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'amadreh/v1/', '/change-password/', array(
          'methods' => WP_REST_Server::CREATABLE,
          'callback' => 'changePassword',
      ) );
});

function createUpdatedUser($POST){
    $user_id = sanitize_text_field( $_POST['user_id'] );
    $email = sanitize_email( $_POST['email'] );
    $first_name = sanitize_text_field( $_POST['first_name'] );
    $last_name = sanitize_text_field( $_POST['last_name'] );
    $city = sanitize_text_field( $_POST['city'] );
    $country = sanitize_text_field($_POST['country']);
    $address = sanitize_text_field( $_POST['address'] );
    $patronym = sanitize_text_field( $_POST['last_name'] );
    $passport = sanitize_text_field( $_POST['passport'] );
    $phone = sanitize_text_field( $_POST['phone'] );
   
    $updatedUser = new customUser();
    $updatedUser->setUserID($user_id)
    ->setEmail($email)
    ->setFirstName($first_name)
    ->setLastName($last_name)
    ->setCountry($country)
    ->setCity($city)
    ->setAddress($address)
    ->setPatronym($patronym)
    ->setPhone($phone);
    
    return $updatedUser;
}


function updateUser(WP_REST_Request $request){
    $updatedUser = createUpdatedUser($_POST);
    complete_update($updatedUser);
}

function complete_update($updatedUser){
        $userdata = [
            'ID' => $updatedUser->user_id,
            'user_email' => $updatedUser->email,
            'user_url' => $updatedUser->personal_site,
        ];
        $user = wp_update_user( $userdata ) ;
        update_usermeta($user, 'first_name', $updatedUser->first_name);
        update_usermeta($user, 'last_name', $updatedUser->last_name);
        update_usermeta($user, 'country', $updatedUser->country);
        update_usermeta($user, 'city', $updatedUser->city);
        update_usermeta($user, 'address', $updatedUser->address);
        update_usermeta($user, 'patronym', $updatedUser->patronym);
        update_usermeta($user, 'phone', $updatedUser->phone);
        wp_send_json_success();
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'amadreh/v1/', '/update-user/', array(
          'methods' => WP_REST_Server::CREATABLE,
          'callback' => 'updateUser',
      ) );
});

function addReservation(WP_REST_Request $request){
    if(isset ($_REQUEST)){
        
        if (isset($_REQUEST['name'])) {
            $name = $_REQUEST['name'];
        } 

        if (isset($_REQUEST['phone'])) {
            $phone = $_REQUEST['phone'];
        }
        
        if (isset($_REQUEST['comment'])) {
            $comment = $_REQUEST['comment'];
        }

        if (isset($_REQUEST['date'])) {
            $date = $_REQUEST['date'];
        }

        if (isset($_REQUEST['time'])) {
            $time = $_REQUEST['time'];
        }

        if (isset($_REQUEST['guests'])) {
            $guests = $_REQUEST['guests'];
        }

        if (isset($_REQUEST['halls'])) {
            $halls = $_REQUEST['halls'];
        }

        if (isset($_REQUEST['table'])) {
            $table = $_REQUEST['table'];
        }

        $title = 'Заказ столика '.$table.' в зале '.$halls.' на '.$date.' '.$time;
     
        $new_post = array(
            'post_title'    => $title,
            'post_content'  => '',
            'post_status'   => 'publish',        
            'post_type' => 'record',
        );

        $pid = wp_insert_post($new_post); 

        update_field('field_5e37e4fd07275', $name, $pid);
        update_field('field_5e37e51307276', $phone, $pid);
        update_field('field_5e37e54807277', $comment, $pid);
        update_field('field_5e37e47707274', $date, $pid);
        update_field('field_5e37e42607273', $time, $pid);
        update_field('field_5e37e410072_guests', $guests, $pid);
        update_field('field_5e37e3f807271', $halls, $pid);
        update_field('field_5e37e41007272', $table, $pid);
 
                        
        wp_send_json_success($orders);
    }
    wp_send_json_error($orders);
}


add_action( 'rest_api_init', function () {
register_rest_route( 'amadreh/v1/', '/add-reservation/', array(
      'methods' => WP_REST_Server::CREATABLE,
      'callback' => 'addReservation',
  ) );
});


function getOrders(WP_REST_Request $request){
    $args_orders = [
        'post_type' => 'record',
        'fields' => 'ids',
        'posts_per_page' => -1,
    ];
    $orders_ids = get_posts( $args_orders );
    $orders = [];

    foreach ($orders_ids as $id) {
        $order = (object)[];
        $order->name = get_field('name', $id);
        $order->phone = get_field('phone', $id);
        $order->comment = get_field('comment', $id);
        $order->date = get_field('date', $id);
        $order->time = get_field('time', $id);
        $order->guests = get_field('guests', $id);
        $order->halls = get_field('hall', $id);
        $order->table = get_field('table', $id);
        $orders[] = $order;
    }
    $response = (object)[];
    $response->orders = $orders;
    wp_send_json_success($response);
}



add_action( 'rest_api_init', function () {
    register_rest_route( 'amadreh/v1/', '/get-orders/', array(
          'methods' => WP_REST_Server::READABLE,
          'callback' => 'getOrders',
      ));
});

function ajax_create_order() {
	// Получить корзину
    $cart = WC()->cart;
    
	$payment_method = esc_attr( trim( $_REQUEST['payment_method'] ) );
	$name = esc_attr( trim( $_REQUEST['first_name'] ) );
	$billing_email = esc_attr( trim( $_REQUEST['email'] ) );
	$phone = esc_attr( trim( $_REQUEST['phone'] ) );
    $shipping_city = esc_attr( trim( $_REQUEST['city'] ) );
    $shipping_street = esc_attr( trim( $_REQUEST['street'] ) );
    $billing_house = esc_attr( trim( $_REQUEST['house'] ) );

    $extra_first_name = esc_attr( $_REQUEST['first_name']);
    $extra_phone = esc_attr( $_REQUEST['phone']);
    $extra_city = esc_attr( $_REQUEST['city']);
    $extra_street = esc_attr( $_REQUEST['street']);
    $extra_house = esc_attr( $_REQUEST['house']);
    $extra_apartment = esc_attr( $_REQUEST['apartment']);
    $extra_intercom = esc_attr( $_REQUEST['intercom']);
    $extra_porch = esc_attr( $_REQUEST['porch']);
    $extra_floor = esc_attr( $_REQUEST['floor']);
    $extra_comment = esc_attr( $_REQUEST['comment']);
    $extra_payment = esc_attr( $_REQUEST['payment']);
    $extra_delivery = esc_attr( $_REQUEST['delivery']);
      
	$address = [
		'first_name' => $name,
		'billing_email' => $billing_email,
		'email' => $email,
		'phone' => $phone,
		'city' => $shipping_city,
		'street' => $shipping_street,
		'address_1'  => $shipping_street,
		'billing_house' => $billing_house,
    ];
    
    $default_password = wp_generate_password();
    // TODO: заменить на телефон
    if(!$billing_email){
        $billing_email = $default_password.'@user.ru';
        $billing_email = 'studio@is-art.ru';
    }
    
    if (!$user = get_user_by('login', $billing_email)) $user_id = wp_create_user( $billing_email, $default_password, $billing_email );

    $order = wc_create_order(
        [
            'customer_id' => $user ? $user->id : $user_id,
            'first_name' => $extra_first_name,
            'phone' => $extra_phone,
            'city' => $extra_city,
            'street' => $extra_street,
            'house' => $extra_house,
            'apartment' => $extra_apartment,
            'intercom' => $extra_intercom,
            'porch' => $extra_porch,
            'floor' => $extra_floor,
            'comment' => $extra_comment,
            'payment' => $extra_payment,
        ]
    );

    $order->update_meta_data( 'first_name', $extra_first_name );
    $order->update_meta_data( 'phone', $extra_phone );
    $order->update_meta_data( 'city', $extra_city );
    $order->update_meta_data( 'street', $extra_street );
    $order->update_meta_data( 'house', $extra_house );
    $order->update_meta_data( 'apartment', $extra_apartment );
    $order->update_meta_data( 'intercom', $extra_intercom );
    $order->update_meta_data( 'porch', $extra_porch );
    $order->update_meta_data( 'floor', $extra_floor );
    $order->update_meta_data( 'comment', $extra_comment );
    $order->update_meta_data( 'payment', $extra_payment );
    $order->update_meta_data( 'delivery', $extra_delivery );
    $order->save();

	// Информация о покупателе
	$order->set_address( $address, 'billing' );
    $order->set_address( $address, 'shipping' );

    

    //Установить тип оплаты
	$order->set_payment_method($payment_method);
	
	// Товары из корзины
	foreach( $cart->get_cart() as $cart_item_key => $cart_item ) {
		$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
		$order->add_product( $_product, $cart_item['quantity'], [
			'variation' => $cart_item['variation'],
			'totals'    => [
				'subtotal'     => $cart_item['line_subtotal'],
				'subtotal_tax' => $cart_item['line_subtotal_tax'],
				'total'        => $cart_item['line_total'],
				'tax'          => $cart_item['line_tax'],
				'tax_data'     => $cart_item['line_tax_data']
			]
		]);
    }
    
	// Добавить купоны
	foreach ( $cart->get_coupons() as $code => $coupon ) {
		$order->add_coupon( $code, $cart->get_coupon_discount_amount( $code ), $cart->get_coupon_discount_tax_amount( $code ) );
	}
    $order->calculate_totals();
    
	// Отправить письмо юзеру
    $mailer = WC()->mailer();
    // TODO: наверно лишния отправка письма
	// $email = $mailer->emails['WC_Email_Customer_Processing_Order'];
    // $email->trigger( $order->get_id() );
    
	// Отправить письмо админу
	$email = $mailer->emails['WC_Email_New_Order'];
    $email->trigger( $order->get_id() );

	// // Очистить корзину
	$cart->empty_cart();

	// // Process Payment
    $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
    
    if($payment_method == "jetpack_custom_gateway"){
        $payment_method = 'cod';
    }

    $result = $available_gateways[ $payment_method ]->process_payment( $order->get_id() );

    // //Redirect to success/confirmation/payment page
    if ( $result['result'] == 'success' ) {
        $result = apply_filters( 'woocommerce_payment_successful_result', $result, $order->get_id() );
    }
	wp_send_json_success( $result );
}
add_action( 'wp_ajax_create_order', 'ajax_create_order' );
add_action( 'wp_ajax_nopriv_create_order', 'ajax_create_order');

//установка количества продукта в корзине по cart id
function set_item_from_cart_by_cart_id() {
    $cart = WC()->instance()->cart;
    $cart_id = $_POST['cart_id'];
    $product_quantity = $_POST['product_quantity'];
    $cart_item_id = $cart->find_product_in_cart($cart_id);
    if($cart_item_id){
       $cart->set_quantity($cart_item_id, $product_quantity);
       $result->total = $cart->cart_contents_total;
       wp_send_json_success($result);
    } 
    wp_send_json_error();
}
add_action('wp_ajax_set_item_from_cart_by_cart_id', 'set_item_from_cart_by_cart_id');
add_action('wp_ajax_nopriv_set_item_from_cart_by_cart_id', 'set_item_from_cart_by_cart_id');

function getAreas(WP_REST_Request $request){
    $areas_raw = get_field('areas', 'options');
    $areas = [];
    foreach ($areas_raw as $k => $area_raw) {
        $object_area = (object)[];
        $object_area->name = $area_raw['name'];
        $object_area->price = $area_raw['price'];

        $new_subareas = [];
	if($area_raw['subareas']){
	    foreach ($area_raw['subareas'] as $subarea) {
		$subarea['price'] = $area_raw['price'];
		$new_subareas[] = $subarea;
	     }
             $object_area->subareas = $new_subareas;
	}
        $areas[] = $object_area;
    }

    $response = $areas;
    wp_send_json_success($response);
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'amadreh/v1/', '/get-areas/', array(
          'methods' => WP_REST_Server::READABLE,
          'callback' => 'getAreas',
      ));
});
