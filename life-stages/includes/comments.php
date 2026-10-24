<?php
/**
 * Article comments: sensible defaults, friendlier form text and light spam protection.
 *
 * Comments use WordPress's built-in comment system, stored in the site's own
 * database (MySQL/MariaDB on most hosts, SQLite in WordPress Playground), with
 * moderation in Dashboard → Comments.
 *
 * @package life-stages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Recommended discussion settings for this blog.
 *
 * @return array option => value
 */
function ls_comment_defaults() {
	return array(
		'default_comment_status'       => 'open',  // Comments on for new articles.
		'require_name_email'           => 1,       // Name and email required (email is never shown).
		'comment_registration'         => 0,       // No account needed to comment.
		'comment_previously_approved'  => 1,       // A person's first comment waits for your approval.
		'comment_moderation'           => 0,       // After that, their comments appear straight away.
		'comment_max_links'            => 1,       // Comments with 2+ links wait for approval.
		'thread_comments'              => 1,       // Replies are nested...
		'thread_comments_depth'        => 3,       // ...up to 3 levels deep.
		'page_comments'                => 1,       // Long discussions are split into pages...
		'comments_per_page'            => 30,
		'default_comments_page'        => 'oldest',
		'comment_order'                => 'asc',
		'show_avatars'                 => 0,       // No third-party avatar images (faster, more private).
		'comments_notify'              => 1,       // Email you when someone comments...
		'moderation_notify'            => 1,       // ...and when a comment needs approval.
		'close_comments_for_old_posts' => 0,
	);
}

/**
 * Apply the recommended discussion settings.
 */
function ls_apply_comment_defaults() {
	foreach ( ls_comment_defaults() as $key => $value ) {
		update_option( $key, $value );
	}
}

/**
 * Comments only on articles, never on pages.
 *
 * @param bool $open    Whether comments are open.
 * @param int  $post_id Post ID.
 * @return bool
 */
function ls_comments_only_on_posts( $open, $post_id ) {
	return 'page' === get_post_type( $post_id ) ? false : $open;
}
add_filter( 'comments_open', 'ls_comments_only_on_posts', 10, 2 );

/**
 * Friendlier comment form text.
 *
 * @param array $d Defaults.
 * @return array
 */
function ls_comment_form_text( $d ) {
	$d['title_reply']          = 'Ask a question or share your experience';
	$d['title_reply_to']       = 'Reply to %s';
	$d['label_submit']         = 'Post comment';
	$d['comment_notes_before'] = '<p class="comment-notes">Your email address is never published. First comments are reviewed before they appear.</p>';
	$d['comment_field']        = '<p class="comment-form-comment"><label for="comment">Your comment <span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="6" maxlength="5000" required></textarea></p>';
	return $d;
}
add_filter( 'comment_form_defaults', 'ls_comment_form_text' );

/**
 * Hidden honeypot field plus a timestamp, to stop most automated spam
 * without CAPTCHAs.
 */
function ls_comment_honeypot_fields() {
	echo '<p class="ls-hp" aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;"><label for="ls_website_url">Leave this field empty</label><input type="text" id="ls_website_url" name="ls_website_url" value="" tabindex="-1" autocomplete="off"></p>';
	echo '<input type="hidden" name="ls_ts" value="' . esc_attr( time() ) . '">';
}
add_action( 'comment_form', 'ls_comment_honeypot_fields' );

/**
 * Reject comments that fill the honeypot or arrive implausibly fast.
 *
 * @param array $data Comment data.
 * @return array
 */
function ls_comment_spam_check( $data ) {
	if ( is_user_logged_in() || ( isset( $data['comment_type'] ) && '' !== $data['comment_type'] && 'comment' !== $data['comment_type'] ) ) {
		return $data;
	}
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- Comment form has no nonce for visitors.
	$hp = isset( $_POST['ls_website_url'] ) ? trim( wp_unslash( $_POST['ls_website_url'] ) ) : '';
	$ts = isset( $_POST['ls_ts'] ) ? (int) $_POST['ls_ts'] : 0;
	// phpcs:enable
	if ( '' !== $hp || ! $ts || ( time() - $ts ) < 3 ) {
		wp_die(
			esc_html__( 'Sorry, your comment could not be posted. Please go back and try again.', 'life-stages' ),
			esc_html__( 'Comment not posted', 'life-stages' ),
			array(
				'response'  => 403,
				'back_link' => true,
			)
		);
	}
	return $data;
}
add_filter( 'preprocess_comment', 'ls_comment_spam_check', 1 );

/**
 * Drop the "Website" field: it is rarely useful here and mostly attracts spam links.
 *
 * @param array $fields Form fields.
 * @return array
 */
function ls_comment_drop_url_field( $fields ) {
	unset( $fields['url'] );
	if ( isset( $fields['cookies'] ) ) {
		$fields['cookies'] = preg_replace( '#<label for="wp-comment-cookies-consent">.*?</label>#s', '<label for="wp-comment-cookies-consent">Save my name and email in this browser for the next time I comment.</label>', $fields['cookies'] );
	}
	return $fields;
}
add_filter( 'comment_form_default_fields', 'ls_comment_drop_url_field' );
