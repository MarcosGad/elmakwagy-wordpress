<?php
get_post_format();
$blog_full_content = apply_filters( 'theme_get_option', 'blog_full_content' );
$css_class         = 'post-item';
if ( $blog_full_content != 1 ) {
	$css_class .= ' item-standard';
}
if ( have_posts() ) :
	while ( have_posts() ) : the_post(); ?>
        <article <?php post_class( $css_class ); ?>>
            <div class="post-header">
				<?php if ( $blog_full_content != 1 ) : ?>
                    <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <ul class="post-meta">
                        <li class="author">
                            <i class="fa fa-user"></i>
                            <span><?php echo esc_html__( 'By ', 'kuteshop' ) ?></span>
                            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>">
								<?php the_author() ?>
                            </a>
                        </li>
						<?php if ( !empty( get_the_terms( get_the_ID(), 'category' ) ) ): ?>
                            <li class="category">
                                <i class="fa fa-folder-o" style="margin-right:5px"></i>
								<?php the_category(); ?>
                            </li>
						<?php endif; ?>
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
                            <i class="fa fa-calendar" aria-hidden="true"></i>
							<?php echo get_the_date(); ?>
                        </li>
						<?php if ( is_sticky() ) : ?>
                            <li class="sticky-post"><i class="fa fa-flag"></i>
								<?php echo esc_html__( ' Sticky', 'kuteshop' ); ?>
                            </li>
						<?php endif; ?>
                    </ul>
				<?php endif; ?>
            </div>
			<?php
			if ( !is_search() ) {
				kuteshop_post_thumbnail();
			}
			?>
            <div class="post-info">
				<?php if ( $blog_full_content == 1 ) : ?>
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
								esc_html__( '0 Comment', 'kuteshop' ),
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
				<?php endif; ?>
                <div class="post-content">
					<?php if ( $blog_full_content == 1 ) :
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
					else :
						echo wp_trim_words( apply_filters( 'the_excerpt', get_the_excerpt() ), 45, esc_html__( '...', 'kuteshop' ) );
					endif; ?>
                </div>
				<?php if ( !empty( get_the_terms( get_the_ID(), 'post_tag' ) ) && $blog_full_content == 1 ) : ?>
                    <div class="tags"><?php the_tags(); ?></div>
				<?php endif; ?>
				<?php if ( $blog_full_content != 1 ) : ?>
                    <a class="button read-more screen-reader-text"
                       href="<?php the_permalink(); ?>"><?php echo esc_html__( 'Read more', 'kuteshop' ); ?></a>
				<?php endif; ?>
            </div>
        </article>
	<?php endwhile;
	wp_reset_postdata();
	kuteshop_paging_nav();
else :
	get_template_part( 'content', 'none' );
endif;