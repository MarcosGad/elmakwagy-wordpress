<?php
/**
 * Reports Template
 *
 * This template can be overridden by copying it to yourtheme/wc-vendors/dashboard/reports.php
 *
 * @author        Jamie Madden, WC Vendors
 * @package       WCVendors/Templates/dashboard
 * @version       2.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wc-vendors-dashboard">

    <h2><?php echo esc_html__( 'Sales Report', 'kuteshop' ); ?></h2>

	<?php

	if ( $datepicker !== 'false' ) {
		wc_get_template(
			'date-picker.php', array(
			'start_date' => $start_date,
			'end_date'   => $end_date,
		), 'wc-vendors/dashboard/', wcv_plugin_dir . 'templates/dashboard/'
		);
	}

	?>

    <table class="table table-condensed table-vendor-sales-report">
        <thead>
        <tr>
            <th class="product-header"><?php echo esc_html__( 'Product', 'kuteshop' ); ?></th>
            <th class="quantity-header"><?php echo esc_html__( 'Quantity', 'kuteshop' ); ?></th>
            <th class="commission-header"><?php echo esc_html__( 'Commission', 'kuteshop' ); ?></th>
            <th class="rate-header"><?php echo esc_html__( 'Rate', 'kuteshop' ); ?></th>
            <th></th>
        </thead>
        <tbody>

		<?php if ( !empty( $vendor_summary ) ) : ?>

			<?php if ( !empty( $vendor_summary['products'] ) ) : ?>

				<?php
				foreach ( $vendor_summary['products'] as $product ) :
					$_product = wc_get_product( $product['id'] );
					?>

                    <tr>

                        <td class="product">
                            <strong>
                                <a href="<?php echo esc_url( get_permalink( $_product->get_id() ) ); ?>">
									<?php echo esc_html( $product['title'] ); ?>
                                </a>
                            </strong>
							<?php
							if ( !empty( $_product->variation_id ) ) {
								echo wc_get_formatted_variation( $_product->variation_data );
							}
							?>
                        </td>
                        <td class="qty"><?php echo esc_html( $product['qty'] ); ?></td>
                        <td class="commission"><?php echo wc_price( $product['cost'] ); ?></td>
                        <td class="rate"><?php echo sprintf( '%.2f%%', $product['commission_rate'] ); ?></td>

						<?php if ( $can_view_orders ) : ?>
                            <td>
                                <a href="<?php echo esc_url( $product['view_orders_url'] ); ?>">
									<?php echo esc_html__( 'Show Orders', 'kuteshop' ); ?>
                                </a>
                            </td>
						<?php endif; ?>

                    </tr>

				<?php endforeach; ?>

                <tr>
                    <td><strong><?php echo esc_html__( 'Totals', 'kuteshop' ); ?></strong></td>
                    <td><?php echo esc_html( $vendor_summary['total_qty'] ); ?></td>
                    <td><?php echo wc_price( $vendor_summary['total_cost'] ); ?></td>
                    <td></td>

					<?php if ( $can_view_orders ) : ?>
                        <td></td>
					<?php endif; ?>

                </tr>

			<?php else : ?>

                <tr>
                    <td colspan="4"
                        style="text-align:center;">
						<?php echo esc_html__( 'You have no sales during this period.', 'kuteshop' ); ?>
                    </td>
                </tr>

			<?php endif; ?>

		<?php else : ?>

            <tr>
                <td colspan="4"
                    style="text-align:center;">
					<?php echo esc_html__( 'You haven\'t made any sales yet.', 'kuteshop' ); ?>
                </td>
            </tr>

		<?php endif; ?>

        </tbody>
    </table>

</div>