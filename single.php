<?php
$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;

$post_type = $post->post_type;
if ($post_type == 'gallery') {
	$gallery = get_field('gallery');
	$gallery_chunks = array_chunk($gallery, 3); 
	$context['gallery_chunks'] = $gallery_chunks;
}

if ( post_password_required( $post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array( 'single-' . $post->ID . '.twig', 'single-' . $post->post_type . '.twig', 'single.twig' ), $context );
}