<?php
$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
$context['current_user'] = new Timber\User();
Timber::render( ['account/account.twig'], $context );

?>

<!-- <div class="my-account-container">
    <div class="my-account-sidebar">
        <div class="my-account-sidebar__title"></div>
		<?php do_action( 'woocommerce_account_navigation' ); ?>
    </div>
    <div class="my-account-content">
        <div class="my-account-content-page">
            <div class="my-account-content-page__title"><? the_title(); ?></div>
            <div class="my-account-content-page__description">
				<?php do_action( 'woocommerce_account_content' ); ?>
            </div>
        </div>
    </div>
</div> -->