<?php
$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
$templates = ['page-' . $post->post_name . '.twig', 'page.twig'];


if (is_cart() || is_checkout() || is_account_page()) {
    array_unshift($templates, 'page-woo.twig');
    Timber::render($templates, $context);
}
else{
    $menu_pages = ["main-menu", "banket-menu", "pomin-menu"];
    if (in_array($post->post_name, $menu_pages)) {
        if ($post->post_name == "main-menu") {
            $name_menu = 'submenu_main';
        }
        if ($post->post_name == "banket-menu") {
            $name_menu = 'submenu_banket';
        }
        if ($post->post_name == "pomin-menu") {
            $name_menu = 'submenu_pomin';
        }
        $menu_option = get_field($name_menu, 'options');
        $context['menu_option'] = $menu_option;
        array_unshift( $templates, 'page-main-menu.twig' );
    }
    
    Timber::render($templates, $context);
}