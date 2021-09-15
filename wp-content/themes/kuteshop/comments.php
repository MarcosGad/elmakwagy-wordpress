<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Kuteshop
 * @since 1.0
 * @version 1.0
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
$fields            = array(
	'name'  => '<p class="comment-reply-content"><input type="text" name="author" id="name" class="input-form name" placeholder="' . esc_html__( 'Your name', 'kuteshop' ) . '"/></p>',
	'email' => '<p class="comment-reply-content"><input type="text" name="email" id="email" class="input-form email" placeholder="' . esc_html__( 'Your email', 'kuteshop' ) . '"/></p>',
);
$comment_field     = '<p class="comment-reply-content"><textarea class="input-form" id="comment" name="comment" cols="45" rows="6" aria-required="true" placeholder="' . esc_html__( 'Your Comments', 'kuteshop' ) . '"></textarea></p>';
$comment_form_args = array(
	'class_submit'  => 'button',
	'comment_field' => $comment_field,
	'fields'        => $fields,
	'label_submit'  => esc_html__( 'Send Comment', 'kuteshop' ),
	'title_reply'   => esc_html__( 'Leave your Comment', 'kuteshop' ),
);
?>
<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
			<?php
			$comments_number = get_comments_number();
			if ( '1' === $comments_number ) {
				/* translators: %s: post title */
				printf( esc_html_x( 'One Reply to &ldquo;%s&rdquo;', '', 'kuteshop' ), get_the_title() );
			} else {
				printf(
				/* translators: 1: number of comments, 2: post title */
					_nx(
						'%1$s Reply to &ldquo;%2$s&rdquo;',
						'%1$s Replies to &ldquo;%2$s&rdquo;',
						$comments_number,
						'',
						'kuteshop'
					),
					number_format_i18n( $comments_number ),
					get_the_title()
				);
			}
			?>
        </h2>
        <ol class="comment-list">
			<?php
			wp_list_comments( array(
					'avatar_size' => 70,
					'style'       => 'ol',
				)
			);
			?>
        </ol>
		<?php
	endif;
	the_comments_pagination( array(
			'screen_reader_text' => '',
			'prev_text'          => '<span class="screen-reader-text">' . esc_html__( 'Prev', 'kuteshop' ) . '</span>',
			'next_text'          => '<span class="screen-reader-text">' . esc_html__( 'Next', 'kuteshop' ) . '</span>',
		)
	);
	if ( !comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="no-comments"><?php echo esc_html__( 'Comments are closed.', 'kuteshop' ); ?></p>
		<?php
	endif;
	comment_form( $comment_form_args );
	?>
</div><!-- #comments -->
