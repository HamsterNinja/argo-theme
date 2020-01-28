<?php
$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

if (is_cart() || is_checkout()) {
    Timber::render( array( 'page-woo.twig' ), $context );
}
else{
    Timber::render( array( 'page-' . $post->post_name . '.twig', 'page.twig' ), $context );
}