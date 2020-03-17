<?php

defined( 'ABSPATH' ) || exit;

$page_title = ( 'billing' === $load_address ) ? esc_html__( 'Billing address', 'woocommerce' ) : esc_html__( 'Shipping address', 'woocommerce' );

do_action( 'woocommerce_before_edit_account_address_form' ); 
?>
<?php if ( ! $load_address ) : ?>
    <?php 
        $customer_id = get_current_user_id();
        if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
            $get_addresses = apply_filters(
                'woocommerce_my_account_get_addresses',
                array(
                    'billing'  => __( 'Billing address', 'woocommerce' ),
                    'shipping' => __( 'Shipping address', 'woocommerce' ),
                ),
                $customer_id
            );
        } else {
            $get_addresses = apply_filters(
                'woocommerce_my_account_get_addresses',
                array(
                    'billing' => __( 'Billing address', 'woocommerce' ),
                ),
                $customer_id
            );
        }

        $array_address = [];
        foreach ( $get_addresses as $name => $address_title ){
            $address = wc_get_account_formatted_address( $name );
            $address_item = (object)[];
            $address_item->address_title = esc_html( $address_title );
            $address_item->edit_address = esc_url( wc_get_endpoint_url( 'edit-address', $name ) );
            $address_item->address = $address ? $address : esc_html__( 'You have not set up this type of address yet.', 'woocommerce' );
            $array_address[] = $address_item;
        }
        $context = Timber::get_context();
        $context['addresses'] = $array_address;
        Timber::render( ['account/edit-address.twig'], $context );
    ?>
<?php else : ?>

	<form method="post">

		<h3><?php echo apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address ); ?></h3><?php // @codingStandardsIgnoreLine ?>

		<div class="woocommerce-address-fields">
			<?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

			<div class="woocommerce-address-fields__field-wrapper">
				<?php
				foreach ( $address as $key => $field ) {
					woocommerce_form_field( $key, $field, wc_get_post_data_by_key( $key, $field['value'] ) );
				}
				?>
			</div>

			<?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

			<p>
				<button type="submit" class="button" name="save_address" value="<?php esc_attr_e( 'Save address', 'woocommerce' ); ?>"><?php esc_html_e( 'Save address', 'woocommerce' ); ?></button>
				<?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
				<input type="hidden" name="action" value="edit_address" />
			</p>
		</div>

	</form>

<?php endif; ?>

<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>
