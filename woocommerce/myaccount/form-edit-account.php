<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); ?>

<div class="account-right-form">
<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
    <label for="account_first_name">
      <p>Имя</p>
      <input id="account_first_name" name="account_first_name" type="text" placeholder="Иван" value="<?php echo esc_attr( $user->first_name ); ?>">
    </label>

    <label for="account_last_name">
      <p>Фамилия</p>
      <input id="account_last_name" name="account_last_name" type="text" placeholder="Иванов" value="<?php echo esc_attr( $user->last_name ); ?>">
    </label>

    <label for="account_email">
      <p>E-Mail</p>
      <input name="account_email" id="account_email"  type="email" placeholder="ivanov@mail.ru" value="<?php echo esc_attr( $user->user_email ); ?>">
    </label>

    <label for="billing_mobile_phone">
      <p>Телефон *</p>
  
      <masked-input type="phone" name="billing_mobile_phone" id="billing_mobile_phone" mask="\+\7 (111) 111-11-11" value="<?php echo esc_attr( $user->billing_mobile_phone ); ?>"></masked-input>
    </label>

    <label for="account_display_name">
      <p>Отображаемое имя *</p>
      <input name="account_display_name" id="account_display_name" type="text" value="<?php echo esc_attr( $user->display_name ); ?>">
    </label>


	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<p>
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button type="submit" class="acoount-submit" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>
</div>
<?php do_action( 'woocommerce_after_edit_account_form' ); ?>