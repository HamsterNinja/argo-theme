<?php	
$context = Timber::get_context();
$context['order_number'] = $order->get_order_number();
Timber::render('woocommerce/page-thanks.twig', $context);