<div class="blog-thumb">
    <a href="<?php the_permalink(); ?>">
		<?php
		$kuteshop_blog_lazy = apply_filters( 'theme_get_option', 'kuteshop_theme_lazy_load' );
		$lazy_check         = $kuteshop_blog_lazy == 1 ? true : false;
		$image_thumb        = apply_filters( 'theme_resize_image', get_post_thumbnail_id(), 270, 255, true, $lazy_check );
		echo '<figure>';
		echo wp_specialchars_decode( $image_thumb['img'] );
        echo '</figure>';
		?>
    </a>
</div>
<div class="blog-info equal-elem">
    <h4 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
    <ul class="blog-meta">
        <li class="date">
            <i class="fa fa-calendar" aria-hidden="true"></i>
			<?php echo get_the_date('M d, Y'); ?>
        </li>
        <li class="comment">
            <i class="fa fa-comment-o" aria-hidden="true"></i>
			<?php comments_number(
				esc_html__( 'No Comments', 'kuteshop' ),
				esc_html__( '1 Comment', 'kuteshop' ),
				esc_html__( '% Comments', 'kuteshop' )
			);
			?>
        </li>
    </ul>
    <div class="blog-readmore">
        <a class="button read-more" href="<?php the_permalink(); ?>">
			<?php echo esc_html__( 'Read more', 'kuteshop' ); ?>
            <span class="fa fa-arrow-right"></span>
        </a>
    </div>
</div>