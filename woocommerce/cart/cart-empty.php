<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="container">
<?php
do_action( 'woocommerce_cart_is_empty' );

if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
	<p class="return-to-shop">
		<a class="button wc-backward" href="<?= get_site_url() ?>/delivery-menu/">
			<?php esc_html_e( 'Return to shop', 'woocommerce' ); ?>
		</a>
	</p>
<?php endif; ?>
</div>