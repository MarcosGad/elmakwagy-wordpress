<?php
/**
 * Date picker Template
 *
 * This template can be overridden by copying it to yourtheme/wc-vendors/dashboard/date-picker.php
 *
 * @author        Jamie Madden, WC Vendors
 * @package       WCVendors/Templates/dashboard
 * @version       2.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}
?>

<form method="post" class="wc-vendors-date-picker">
    <p>
        <label style="display:inline;" for="from">
			<?php echo esc_html__( 'From:', 'kuteshop' ); ?>
        </label>
        <input class="date-pick" type="date" name="start_date" id="from"
               value="<?php echo esc_attr( date( 'Y-m-d', $start_date ) ); ?>"/>

        <label style="display:inline;" for="to">
			<?php echo esc_html__( 'To:', 'kuteshop' ); ?>
        </label>
        <input type="date" class="date-pick" name="end_date" id="to"
               value="<?php echo esc_attr( date( 'Y-m-d', $end_date ) ); ?>"/>

        <input type="submit" class="btn btn-inverse btn-small" style="float:none;"
               value="<?php echo esc_html__( 'Show', 'kuteshop' ); ?>"/>
    </p>
</form>
