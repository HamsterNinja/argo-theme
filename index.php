<?php
$context = Timber::get_context();
$templates = ['index.twig'];
if ( is_home() ) {    
	array_unshift( $templates, 'home.twig' );
	
	$args_activities = [
		'post_type' => 'activity',
		'posts_per_page' => 3,
	];
	$context['activities'] = Timber::get_posts( $args_activities );

	$popular_args = [
		'post_type' => 'product',
		'posts_per_page' => 12,
		'meta_key' => 'total_sales',
    	'orderby' => 'meta_value_num',
	];
	$popular_products = new Timber\PostQuery($popular_args);
	$popular_products_ids = wp_list_pluck( $popular_products, 'ID' );                    
	$context['popular_products'] = $popular_products;
	$context['popular_products_ids'] = $popular_products_ids;

	Timber::render( $templates, $context );
}
else{
	Timber::render( $templates, $context );
}
?>