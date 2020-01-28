<?php	
$context = Timber::get_context();
$context['is_user_logged_in'] = is_user_logged_in();
$context['woocommerce_create_account_default_checked'] = apply_filters('woocommerce_create_account_default_checked', false);
$context['checkout_url'] = $get_checkout_url;
$context['checkout'] = WC()->checkout();
$context['cart'] = WC()->cart;
Timber::render('woocommerce/checkout.twig', $context);