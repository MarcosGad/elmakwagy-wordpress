<?php
/**
 * Settings Template
 *
 * This template can be overridden by copying it to yourtheme/wc-vendors/dashboard/settings/settings.php
 *
 * @author        Jamie Madden, WC Vendors
 * @package       WCVendors/Templates/Emails/HTML
 * @version       2.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wcv-shop-settings">

    <h2><?php echo esc_html__( 'Settings', 'kuteshop' ); ?></h2>

	<?php
	if ( function_exists( 'wc_print_notices' ) ) {
		wc_print_notices();
	}
	?>

    <form method="post">
		<?php

		do_action( 'wcvendors_settings_before_paypal' );

		if ( $paypal_address !== 'false' ) {
			wc_get_template(
				'paypal-email-form.php', array(
				'user_id' => $user_id,
			), 'wc-vendors/dashboard/settings/', wcv_plugin_dir . 'templates/dashboard/settings/'
			);
		}

		do_action( 'wcvendors_settings_after_paypal' );

		?>

		<?php do_action( 'wcvendors_settings_before_bank_details', $user_id ); ?>

		<?php if ( apply_filters( 'wcvendors_vendor_dashboard_bank_details_enable', true ) ) : ?>

            <h3><?php echo esc_html__( 'Bank Details', 'kuteshop' ); ?></h3>
            <table>
                <tr>
                    <td><p class="form-row notes">
                            <label for="">
								<?php echo esc_html__( 'Account Name', 'kuteshop' ); ?>
                            </label>
                            <input type="text" name="wcv_bank_account_name" id="wcv_bank_account_name"
                                   value="<?php echo get_user_meta( $user_id, 'wcv_bank_account_name', true ); ?>"/>
                        </p>
                    </td>
                    <td><p class="form-row notes">
                            <label for="wcv_bank_account_number">
								<?php echo esc_html__( 'Account Number', 'kuteshop' ); ?>
                            </label>
                            <input type="text" name="wcv_bank_account_number" id="wcv_bank_account_number"
                                   value="<?php echo get_user_meta( $user_id, 'wcv_bank_account_number', true ); ?>"/>
                        </p>
                    </td>
                    <td><p class="form-row notes">
                            <label for="wcv_bank_name">
								<?php echo esc_html__( 'Bank Name', 'kuteshop' ); ?>
                            </label>
                            <input type="text" name="wcv_bank_name" id="wcv_bank_name"
                                   value="<?php echo get_user_meta( $user_id, 'wcv_bank_name', true ); ?>"/>
                        </p></td>
                </tr>
                <tr>
                    <td><p class="form-row notes"><label
                                    for="wcv_bank_routing_number"><?php echo esc_html__( 'Routing Number', 'kuteshop' ); ?></label><input
                                    type="text" name="wcv_bank_routing_number" id="wcv_bank_routing_number"
                                    value="<?php echo get_user_meta( $user_id, 'wcv_bank_routing_number', true ); ?>"/>
                        </p>
                    </td>
                    <td><p class="form-row notes">
                            <label for="wcv_bank_iban">
								<?php echo esc_html__( 'IBAN', 'kuteshop' ); ?>
                            </label>
                            <input type="text" name="wcv_bank_iban" id="wcv_bank_iban"
                                   value="<?php echo get_user_meta( $user_id, 'wcv_bank_iban', true ); ?>"/>
                        </p></td>
                    <td><p class="form-row notes">
                            <label for="wcv_bank_bic_swift">
								<?php echo esc_html__( 'BIC / Swift', 'kuteshop' ); ?>
                            </label>
                            <input type="text" name="wcv_bank_bic_swift" id="wcv_bank_bic_swift"
                                   value="<?php echo get_user_meta( $user_id, 'wcv_bank_bic_swift', true ); ?>"/></p>
                    </td>
                </tr>
            </table>

			<?php do_action( 'wcvendors_settings_after_bank_details', $user_id ); ?>

		<?php endif; ?>

		<?php

		wc_get_template(
			'shop-name.php', array(
			'user_id' => $user_id,
		), 'wc-vendors/dashboard/settings/', wcv_plugin_dir . 'templates/dashboard/settings/'
		);

		do_action( 'wcvendors_settings_after_shop_name' );

		wc_get_template(
			'seller-info.php', array(
			'global_html' => $global_html,
			'has_html'    => $has_html,
			'seller_info' => $seller_info,
		), 'wc-vendors/dashboard/settings/', wcv_plugin_dir . 'templates/dashboard/settings/'
		);

		do_action( 'wcvendors_settings_after_seller_info' );

		if ( $shop_description !== 'false' ) {
			wc_get_template(
				'shop-description.php', array(
				'description' => $description,
				'global_html' => $global_html,
				'has_html'    => $has_html,
				'shop_page'   => $shop_page,
				'user_id'     => $user_id,
			), 'wc-vendors/dashboard/settings/', wcv_plugin_dir . 'templates/dashboard/settings/'
			);

			do_action( 'wcvendors_settings_after_shop_description' );
		}
		?>

		<?php wp_nonce_field( 'save-shop-settings', 'wc-product-vendor-nonce' ); ?>
        <input type="submit" class="btn btn-inverse btn-small" style="float:none;" name="vendor_application_submit"
               value="<?php echo esc_html__( 'Save', 'kuteshop' ); ?>"/>
    </form>

</div>