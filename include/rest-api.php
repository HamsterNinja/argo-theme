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
        wp_send_json_success();
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
function ajax_login(){
    $info = [];
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    $info['remember'] = true;
    $user_signon = wp_signon( $info, false );
    if ( is_wp_error($user_signon) ){
        echo json_encode(
            [
                'loggedin'=>false,
                'message'=>__('Неверное имя пользователя или пароль')
            ]
        );
    } else {
        echo json_encode(
            [
                'loggedin'=> true,
                'message'=>__('Авторизация успешна')
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