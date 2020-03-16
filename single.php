<?php
$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;

$post_type = $post->post_type;
if ($post_type == 'gallery') {
	$gallery = get_field('gallery');
	$context['gallery'] = $gallery;
}

if ( post_password_required( $post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array( 'single-' . $post->ID . '.twig', 'single-' . $post->post_type . '.twig', 'single.twig' ), $context );
}