<?php
$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;

if (is_cart() || is_checkout() || is_account_page()) {
    Timber::render( ['page-woo.twig'], $context );
}
else{
    Timber::render( ['page-' . $post->post_name . '.twig', 'page.twig'], $context );
}