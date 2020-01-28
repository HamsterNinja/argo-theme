<?php
$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;
function wpb_set_post_views( $post_id ) {
    if ( false === ( $count = get_post_meta( $post_id, 'wpb_post_views_count', true ) ) ){
        $count = 0;
    }
    update_post_meta( $post_id, 'wpb_post_views_count', $count + 1 );
}
wpb_set_post_views($post->ID);

$likes = get_post_meta( $post->ID, '_pt_likes', true );
$context['likes'] = $likes;

if ( post_password_required( $post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array( 'single-' . $post->ID . '.twig', 'single-' . $post->post_type . '.twig', 'single.twig' ), $context );
}