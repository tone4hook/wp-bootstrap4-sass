
	<?php if (post_password_required()) : ?>
	<p><?php _e( 'Post is password protected. Enter the password to view any comments.', 'wpbootstrapsass' ); ?></p>
</div>

	<?php return; endif; ?>

<?php if (have_comments()) : ?>

	<h2><?php comments_number(); ?></h2>

	<ul>
		<?php wp_list_comments('type=comment&callback=wpbootstrapsasscomments'); // Custom callback in functions.php ?>
	</ul>

<?php elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

	<p><?php _e( 'Comments are closed here.', 'wpbootstrapsass' ); ?></p>

<?php endif; ?>

<?php

	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );

	$fields =  array(

	  'author' =>
	    '<p class="comment-form-author"><div class="form-group"><label for="author">' . __( 'Name', 'domainreference' ) . '</label> ' .
	    ( $req ? '<span class="required">*</span>' : '' ) .
	    '<input id="author" class="form-control" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
	    '" size="30"' . $aria_req . ' /></div></p>',

	  'email' =>
	    '<p class="comment-form-email"><div class="form-group"><label for="email">' . __( 'Email', 'domainreference' ) . '</label> ' .
	    ( $req ? '<span class="required">*</span>' : '' ) .
	    '<input id="email" class="form-control" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
	    '" size="30"' . $aria_req . ' /></div></p>',

	  'url' =>
	    '<p class="comment-form-url"><div class="form-group"><label for="url">' . __( 'Website', 'domainreference' ) . '</label>' .
	    '<input id="url" class="form-control" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
	    '" size="30" /></div></p>',
	);

	$comments_args = array(
        'class_form' => 'well', 'class_submit' => 'btn btn-primary',
        'comment_field' => '<p class="comment-form-comment"><div class="form-group"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><br/><textarea id="comment" name="comment" aria-required="true" class="form-control" rows="3"></textarea></div></p>',
        'fields' => apply_filters( 'comment_form_default_fields', $fields ),
	);
?>

<?php comment_form( $comments_args ); ?>
