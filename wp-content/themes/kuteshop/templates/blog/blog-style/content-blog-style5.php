<div class="blog-thumb">
	<a href="<?php the_permalink(); ?>">
		<?php
		$kuteshop_blog_lazy = apply_filters( 'theme_get_option', 'kuteshop_theme_lazy_load' );
		$lazy_check         = $kuteshop_blog_lazy == 1 ? true : false;
		$image_thumb        = apply_filters( 'theme_resize_image', get_post_thumbnail_id(), 290, 288, true, $lazy_check );
        echo '<figure>';
		echo wp_specialchars_decode( $image_thumb['img'] );
        echo '</figure>';
		?>
	</a>
</div>
<div class="blog-info equal-elem">
    <div class="blog-date"><?php echo get_the_date('d F, Y'); ?></div>
	<h4 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
	<a class="button read-more" href="<?php the_permalink(); ?>">
		<?php echo esc_html__( 'Read more', 'kuteshop' ); ?>
	</a>
</div>