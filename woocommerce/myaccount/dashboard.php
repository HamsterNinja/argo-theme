<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters( 'woocommerce_my_account_get_addresses', array(
		'billing' => __( 'Billing address', 'woocommerce' ),
		'shipping' => __( 'Shipping address', 'woocommerce' ),
	), $customer_id );
} else {
	$get_addresses = apply_filters( 'woocommerce_my_account_get_addresses', array(
		'billing' => __( 'Billing address', 'woocommerce' ),
	), $customer_id );
}

?>

<div class="my-account-content-page-description__hello">
	<?php printf('Привет, %1$s!', esc_html( $current_user->display_name ));?>
</div>

<div class="my-account-content-page-description__hello-content">
	На панели инструментов «Моя учетная запись» у вас есть возможность просмотреть снимок вашей недавней активности в
	учетной записи и обновить информацию о вашей учетной записи. Выберите ссылку ниже для просмотра
	или редактирования информации.
</div>

<div class="my-account-navigate">
	<div class="my-account-navigate__title">
		<img src="<?= get_template_directory_uri() ?>/assets/images/user.svg" alt="">
		Информация об учетной записи
	</div>
	<div class="my-account-navigate-items">
		<div class="my-account-navigate-item my-account-navigate-item--edit-account">
			<div class="my-account-navigate-item-title">
				<div class="my-account-navigate-item-title-name">Контакты</div>
				<a href="<?= esc_url( wc_get_endpoint_url( 'edit-account' ) )?>" class="my-account-navigate-item-title-edit">Редактировать</a>
			</div>
			<div class="my-account-navigate-item-content-items">
				<div class="my-account-navigate-item-content-item">
					<p><?= esc_html( $current_user->display_name ); ?></p>
					<p><?= esc_html( $current_user->user_email ); ?></p>
					<a href="<?= esc_url( wc_get_endpoint_url( 'edit-account' ) )?>">Сменить пароль</a>
				</div>
			</div>
		</div>
		<div class="my-account-navigate-item my-account-navigate-item--subscription">
			<div class="my-account-navigate-item-title">
				<div class="my-account-navigate-item-title-name">Информация о подписке</div>
				<a href="<?= esc_url( wc_get_endpoint_url( 'subscription' ) )?>" class="my-account-navigate-item-title-edit">Редактировать</a>
			</div>
			<div class="my-account-navigate-item-content-items">
				<div class="my-account-navigate-item-content-item">
					<p>В настоящие время вы не подписаны на рассылку</p>
				</div>
			</div>
		</div>
		<div class="my-account-navigate-item my-account-navigate-item--address">
			<div class="my-account-navigate-item-title">
				<div class="my-account-navigate-item-title-name">Адресная книга</div>
				<a href="<?= esc_url( wc_get_endpoint_url( 'edit-address' ) )?>" class="my-account-navigate-item-title-edit">Управление адресами</a>
			</div>
			<div class="my-account-navigate-item-content-items">
			<?php foreach ( $get_addresses as $name => $title ) : ?>
				<? if($title == 'Адрес доставки'):?>
				<div class="my-account-navigate-item-content-item">
					<div class="my-account-navigate-item-content-item__title">Адрес доставки</div>
					<p>
						<?php $address = wc_get_account_formatted_address( $name );
						echo $address ? wp_kses_post( $address ) : esc_html_e( 'You have not set up this type of address yet.', 'woocommerce' );
						?>
					</p>
					<a href="<?= esc_url( wc_get_endpoint_url( 'edit-address/shipping/' ) )?>">Изменить адрес</a>
				</div>
				<? endif; ?>

				<? if($title == 'Платёжный адрес' && false):?>
				<div class="my-account-navigate-item-content-item">
					<div class="my-account-navigate-item-content-item__title">Основной платежный адрес</div>
					<p>
						<?php $address = wc_get_account_formatted_address( $name );
						echo $address ? wp_kses_post( $address ) : esc_html_e( 'You have not set up this type of address yet.', 'woocommerce' );
						?>
					</p>
					<a href="<?= esc_url( wc_get_endpoint_url( 'edit-address/billing' ) )?>">Изменить адрес</a>
				</div>
				<? endif; ?>
			<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */