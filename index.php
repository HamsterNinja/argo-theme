<?php
$context = Timber::get_context();
$templates = ['index.twig'];
if ( is_home() ) {    
	array_unshift( $templates, 'home.twig' );
	Timber::render( $templates, $context );
}
else{
	Timber::render( $templates, $context );
}
?>