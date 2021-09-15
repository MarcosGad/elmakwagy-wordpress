<?php
$all_socials = kuteshop_get_option( 'user_all_social' );
$socials     = kuteshop_get_option( 'header_social' );
if ( !empty( $socials ) ) :
	?>
    <div class="header-social">
        <ul class="socials">
			<?php foreach ( $socials as $social ) :
				$array_social = $all_socials[$social]; ?>
                <li class="social-item">
                    <a href="<?php echo esc_url( $array_social['link_social'] ) ?>"
                       target="_blank">
                        <i class="<?php echo esc_attr( $array_social['icon_social'] ); ?>"></i>
                    </a>
                </li>
			<?php endforeach; ?>
        </ul>
    </div>
<?php
endif;