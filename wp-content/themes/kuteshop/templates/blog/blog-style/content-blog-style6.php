<div class="blog-info equal-elem">
    <a class="post-content" href="<?php the_permalink(); ?>">
		<?php echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 12, esc_html__( '', 'kuteshop' ) ); ?>
    </a>
    <a class="the-author" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>">
        @<?php the_author(); ?>
    </a>
    <p class="time-post"><?php kuteshop_time_ago(); ?></p>
</div>