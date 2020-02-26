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