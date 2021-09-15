<article <?php post_class( 'post-item post-single' ); ?>>

	<?php kuteshop_post_thumbnail(); ?>

    <div class="post-info">
        <div class="post-header">
            <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <ul class="post-meta">
                <li class="author">
                    <i class="fa fa-user"></i>
                    <span><?php echo esc_html__( 'By: ', 'kuteshop' ) ?></span>
                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>">
                        <?php the_author() ?>
                    </a>
                </li>
                <li class="category">
                    <i class="fa fa-folder-o" style="margin-right:5px"></i>
                    <?php the_category(); ?>
                </li>
                <li class="comment">
                    <i class="fa fa-comment-o"></i>
                    <?php comments_number(
                        esc_html__( 'No Comments', 'kuteshop' ),
                        esc_html__( '1 Comment', 'kuteshop' ),
                        esc_html__( '% Comments', 'kuteshop' )
                    );
                    ?>
                </li>
                <li class="date">
                    <i class="fa fa-calendar"></i>
                    <?php echo get_the_date(); ?>
                </li>
                <?php if ( is_sticky() ) : ?>
                    <li class="sticky-post"><i class="fa fa-flag"></i>
                        <?php echo esc_html__( ' Sticky', 'kuteshop' ); ?>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="post-content">
			<?php
			/* translators: %s: Name of current post */
			the_content( sprintf(
					esc_html__( 'Continue reading %s', 'kuteshop' ),
					the_title( '<span class="screen-reader-text">', '</span>', false )
				)
			);
			wp_link_pages( array(
					'before'      => '<div class="post-pagination"><span class="title">' . esc_html__( 'Pages:', 'kuteshop' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
				)
			);
			?>
        </div>
		<?php if ( !empty( get_the_terms( get_the_ID(), 'post_tag' ) ) ) : ?>
            <div class="tags"><?php the_tags(); ?></div>
		<?php endif; ?>
    </div>
</article>
<?php get_template_part( 'templates/blog/blog', 'related' );