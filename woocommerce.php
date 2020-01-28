<?php

if ( ! class_exists( 'Timber' ) ){
    echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
    return;
}
$context = Timber::get_context();
$templates = array( 'archive-product.twig' );


// WooCommerce Notices
$context['wc_notices'] = wc_get_notices();
wc_clear_notices();

if ( is_singular( 'product' ) ) {
    $context['post'] = new TimberPost();
    $product = wc_get_product( $context['post']->ID );
    $context['product'] = $product;

    //images product
    $attachment_ids = $product->get_gallery_image_ids();
    $context['attachment_ids'] = $attachment_ids;
    $context['regular_price'] = $product->get_regular_price();
    $context['sale_price'] = $product->get_sale_price();

    $attributes = $product->get_attributes();
    $product_attributes = [];
    foreach ( $attributes as $attribute ) {
        $name = $attribute->get_name();
        $taxonomy = get_taxonomy( $name );
        $labels = get_taxonomy_labels( $taxonomy );
        $singular_name = $labels->singular_name;
        $attribute_object = new stdClass();
        $attribute_object->singular_name = $singular_name;   
        $attribute_values = get_the_terms( $product->ID, $name );        
        if( is_array( $attribute_values ) ){
            foreach( $attribute_values as $current_term ){
                $attribute_object->link = get_term_link($current_term->term_id, $current_term->taxonomy);
                $attribute_object->name = $current_term->name;
            }
        }
        array_push($product_attributes, $attribute_object);       
    }
    $context['product_attributes'] = $product_attributes;

    $args_product_variation = array(
        'post_type'     => 'product_variation',
        'post_status'   => array( 'private', 'publish' ),
        'numberposts'   => -1,
        'orderby'       => 'menu_order',
        'order'         => 'ASC',
        'post_parent'   => $context['post']->ID
    );
    $product_variations_query = get_posts( $args_product_variation );     
    $product_variations = [];
    foreach ( $product_variations_query as $variation ) {
        $variation_ID = $variation->ID;
        $product_variation = new WC_Product_Variation( $variation_ID );
        $product_variation_object = new stdClass();
        $product_variation_object->id = $product_variation->ID;
        $product_variation_object->image = $product_variation->get_image(); 
        $product_variation_object->price = $product_variation->get_price_html();
        $product_variation_object_value = $product_variation->get_variation_attributes();
        $product_variation_object->value = $product_variation_object_value['attribute_pa_sizes'];        
        array_push($product_variations, $product_variation_object);  
    }
    $context['product_variations'] = $product_variations;   

    $context['sku'] = $product->get_sku();

    $args_comments = array ('post_type' => 'product', 'post_id' => $context['post']->ID);
    $comments = get_comments( $args_comments );
    $average_rating_array = [];
    foreach ($comments as $comment) {
        $rating_qualityte = get_comment_meta ( $comment->comment_ID, 'rating_quality', true );
        $rating_service = get_comment_meta ( $comment->comment_ID, 'rating_service', true );
        $rating_delivery = get_comment_meta ( $comment->comment_ID, 'rating_delivery', true );
        $array_rating = [$rating_qualityte, $rating_service, $rating_delivery];
        $average = ceil( array_sum($array_rating) / count($array_rating) );
        array_push($average_rating_array, $average);
    }

    if (!empty($average_rating_array)) {
        $average_product_rating = ceil( array_sum($average_rating_array) / count($average_rating_array) );
    }
    
    $context['comments'] = $comments;
    $context['average_product_rating'] = $average_product_rating ? $average_product_rating : 0;

    Timber::render( 'single-product.twig', $context );
} else {
    $posts = Timber::get_posts();
    $context['products'] = $posts;

    $context['title'] = 'Магазин';

    if (get_queried_object()->taxonomy == 'product_cat') {
        $context['current_product_cat'] = get_queried_object()->slug;
    }

    if (get_queried_object()->taxonomy == 'brand_product') {
        $queried_object = get_queried_object();
        $term_id = $queried_object->term_id;
        $term = get_term( $term_id, 'brand_product' );
        $context['category'] = $term;
        $context['category_slug'] = $term->slug;
        $context['category_count'] = $term->count;
        $context['title'] = single_term_title( '', false );
    }

    if ( is_product_category() ) {
        $queried_object = get_queried_object();
        $term_id = $queried_object->term_id;
        $term = get_term( $term_id, 'product_cat' );
        $context['category'] = $term;
        $context['category_slug'] = $term->slug;
        $context['category_count'] = $term->count;
        $context['title'] = single_term_title( '', false );
        array_unshift( $templates, 'taxonomy-product_cat-' . $term->slug . '.twig' );
    }

    Timber::render( $templates, $context );
}