<?
do_action( 'woocommerce_before_account_orders', $has_orders );

$context = Timber::get_context();
$context['has_orders'] = $has_orders;

Timber::render( ['account/orders.twig'], $context );
?>