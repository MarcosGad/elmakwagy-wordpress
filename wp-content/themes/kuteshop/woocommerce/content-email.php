<?php
/**
 * Template Email
 *
 * @return string
 *
 * @var $product_title
 * @var $product_permalink
 * @var $friend_name
 * @var $email_heading
 * @var $email
 */
?>
<?php
/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email );
?>
    <div style="background-color:#fff;width:650px;font-family:Open-sans,sans-serif;color:#555454;font-size:13px;line-height:18px;margin:auto">
        <table style="width:100%;margin-top:10px">
            <tbody>
            <tr>
                <td align="center" style="border:none;padding:7px 0">
                <span style="font-weight:500;font-size:28px;text-transform:uppercase;line-height:33px">
                    <?php
					echo sprintf( '%s %s', esc_html__( 'Hi', 'kuteshop' ), $friend_name );
					?>
                </span>
                </td>
            </tr>
            <tr>
                <td style="padding:0!important;border:none">&nbsp;</td>
            </tr>
            <tr>
                <td style="background-color:#fbfbfb;border:1px solid #d6d4d4!important;padding:10px!important">
                    <p style="margin:3px 0 7px;text-transform:uppercase;font-weight:500;font-size:16px;border-bottom:1px solid #d6d4d4!important;padding-bottom:10px;line-height:1.4">
						<?php esc_html_e( 'A friend has sent you a link to a product that (s)he thinks may interest you.',
							'kuteshop'
						); ?>
                    </p>
                    <p style="color:#777;margin-bottom:3px;">
						<?php esc_html_e( 'Click here to view this item:', 'kuteshop' ); ?>
                        <a href="<?php echo esc_url( $product_permalink ); ?>" style="color:#337ff1" target="_blank">
							<?php echo esc_html( $product_title ); ?>
                        </a>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="padding:0!important;border:none">&nbsp;</td>
            </tr>
            </tbody>
        </table>
    </div>
<?php
/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );