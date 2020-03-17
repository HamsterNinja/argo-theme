<?
do_action( 'woocommerce_before_account_orders', $has_orders );

$customer_orders = get_posts(
	apply_filters(
		'woocommerce_my_account_my_orders_query',
		array(
			'numberposts' => $order_count,
			'meta_key'    => '_customer_user',
			'meta_value'  => get_current_user_id(),
			'post_type'   => wc_get_order_types( 'view-orders' ),
			'post_status' => array_keys( wc_get_order_statuses() ),
		)
	)
);

$order_items = [];
foreach ( $customer_orders as $customer_order ) {    
    $order      = wc_get_order( $customer_order ); 
	$item_count = $order->get_item_count();
	$order_item = (object)[];
	$order_item->url = esc_url( $order->get_view_order_url() );
	$order_item->number = $order->get_order_number();
	$order_item->date = esc_html( wc_format_datetime( $order->get_date_created() ) );
	$order_item->status = esc_html( wc_get_order_status_name( $order->get_status() ) );
	$order_item->total = $order->get_formatted_order_total();
	$order_items[] = $order_item;
}

$context = Timber::get_context();
$context['has_orders'] = $has_orders;
$context['order_items'] = $order_items;

Timber::render( ['account/orders.twig'], $context );
?>