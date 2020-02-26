<?php
$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
$context['current_user'] = new Timber\User();
$context['edit_account_link'] = esc_url(wc_get_endpoint_url('edit-account'));
$context['edit_address_link'] = esc_url(wc_get_endpoint_url('edit-address'));
$context['notifications_link'] = esc_url(wc_get_endpoint_url('notifications'));
$context['change_password_link'] = esc_url(wc_get_endpoint_url('change_password'));
$context['orders_link'] = esc_url(wc_get_endpoint_url('orders'));

Timber::render( ['account/account.twig'], $context );
?>