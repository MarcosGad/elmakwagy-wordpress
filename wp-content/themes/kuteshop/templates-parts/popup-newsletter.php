<?php
$kuteshop_enable_popup            = kuteshop_get_option( 'kuteshop_enable_popup' );
$kuteshop_popup_title             = kuteshop_get_option( 'kuteshop_popup_title', 'signup for our newsletter & promotions' );
$kuteshop_popup_highlight         = kuteshop_get_option( 'kuteshop_popup_highlight', '' );
$kuteshop_popup_desc              = kuteshop_get_option( 'kuteshop_popup_desc', '' );
$kuteshop_popup_input_placeholder = kuteshop_get_option( 'kuteshop_popup_input_placeholder', 'Enter your email...' );
$kuteshop_poppup_background       = kuteshop_get_option( 'kuteshop_poppup_background', '' );
$kuteshop_blog_lazy               = kuteshop_get_option( 'kuteshop_theme_lazy_load' );
$lazy_check                       = $kuteshop_blog_lazy == 1 ? true : false;
if ( $kuteshop_enable_popup == 1 ) :
	?>
    <!--  Popup Newsletter-->
    <div class="modal fade" id="popup-newsletter" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="pe-7s-close"></i>
                </button>
                <div class="modal-inner">
					<?php if ( $kuteshop_poppup_background ) : ?>
                        <div class="modal-thumb">
							<?php
							$image_thumb = apply_filters( 'theme_resize_image', $kuteshop_poppup_background, 400, 500, true, $lazy_check );
							echo wp_specialchars_decode( $image_thumb['img'] );
							?>
                        </div>
					<?php endif; ?>
                    <div class="modal-info">
						<?php if ( $kuteshop_popup_title ): ?>
                            <h2 class="title"><?php echo esc_html( $kuteshop_popup_title ); ?></h2>
						<?php endif;
						if ( $kuteshop_popup_highlight ) : ?>
                            <p class="highlight"><?php echo wp_specialchars_decode( $kuteshop_popup_highlight ); ?></p>
						<?php endif;
						if ( $kuteshop_popup_desc ): ?>
                            <p class="des"><?php echo esc_html( $kuteshop_popup_desc ); ?></p>
						<?php endif; ?>
                        <div class="newsletter-form-wrap">
                            <input class="email" type="email" name="email"
                                   placeholder="<?php echo esc_html( $kuteshop_popup_input_placeholder ); ?>">
                            <button type="submit" name="submit_button" class="btn-submit submit-newsletter">
                                <span class="pe-7s-check"></span>
                            </button>
                        </div>
                        <div class="checkbox btn-checkbox">
                            <label>
                                <input class="kuteshop_disabled_popup_by_user" type="checkbox">
                                <span><?php echo esc_html__( 'Don&rsquo;t show this popup again', 'kuteshop' ); ?></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--  Popup Newsletter-->
<?php endif;