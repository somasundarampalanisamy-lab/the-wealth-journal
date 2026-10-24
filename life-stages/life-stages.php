<?php
/**
 * Plugin Name: Life Stages
 * Description: Adds a "Stage" audience taxonomy (Starting Out, Growing, Building Wealth), learning paths, a "Next step in your stage" box, stage-aware newsletter forms, free calculators and optional sample content. Pairs with the Stages Finance theme.
 * Version: 1.1.0
 * Requires at least: 6.5
 * Requires PHP: 7.4
 * Author: [Your Name]
 * License: GPL-2.0-or-later
 * Text Domain: life-stages
 *
 * @package life-stages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'LS_VERSION', '1.1.0' );
define( 'LS_DIR', plugin_dir_path( __FILE__ ) );
define( 'LS_URL', plugin_dir_url( __FILE__ ) );

/**
 * All stages, in order, whether or not they are switched on.
 *
 * - label: the stage name.
 * - hub:   slug of the stage's hub page.
 * - who:   the audience title shown on cards ("who is this for").
 * - about: a longer description of the audience.
 *
 * @return array
 */
function ls_all_stages() {
	return array(
		'starting-out'    => array(
			'label' => 'Starting Out',
			'hub'   => 'starting-out',
			'who'   => 'From campus to first payslip',
			'about' => 'Final-year students and anyone in their first year of work.',
		),
		'growing'         => array(
			'label' => 'Growing',
			'hub'   => 'growing',
			'who'   => 'Settled in, levelling up',
			'about' => 'One to five years into your career, with savings that are starting to grow.',
		),
		'building-wealth' => array(
			'label' => 'Building Wealth',
			'hub'   => 'building-wealth',
			'who'   => 'Established and compounding',
			'about' => 'Five or more years in, with a portfolio worth protecting.',
		),
	);
}

/**
 * Slugs of the stages that are switched on (Settings → Life Stages).
 * Building Wealth is off by default; it is kept for a future premium tier.
 *
 * @return string[]
 */
function ls_enabled_stage_slugs() {
	$opts = get_option( 'ls_settings', array() );
	$on   = isset( $opts['stages'] ) && is_array( $opts['stages'] ) ? $opts['stages'] : array( 'starting-out', 'growing' );
	return array_values( array_intersect( array_keys( ls_all_stages() ), $on ) );
}

/**
 * Whether a stage is switched on.
 *
 * @param string $slug Stage slug.
 * @return bool
 */
function ls_stage_enabled( $slug ) {
	return in_array( $slug, ls_enabled_stage_slugs(), true );
}

/**
 * Stages that are switched off.
 *
 * @return string[]
 */
function ls_disabled_stage_slugs() {
	return array_values( array_diff( array_keys( ls_all_stages() ), ls_enabled_stage_slugs() ) );
}

/**
 * The enabled stages, in order, each with its 1-based position and the next enabled stage.
 *
 * @return array
 */
function ls_stages() {
	$all     = ls_all_stages();
	$enabled = ls_enabled_stage_slugs();
	$out     = array();
	foreach ( $enabled as $i => $slug ) {
		$out[ $slug ]          = $all[ $slug ];
		$out[ $slug ]['step']  = $i + 1;
		$out[ $slug ]['total'] = count( $enabled );
		$out[ $slug ]['next']  = isset( $enabled[ $i + 1 ] ) ? $enabled[ $i + 1 ] : '';
	}
	return $out;
}

/* -------------------------------------------------------------------------
 * Taxonomy + learning-path meta
 * ---------------------------------------------------------------------- */

/**
 * Register the Stage taxonomy and the learning-path position meta.
 */
function ls_register() {
	register_taxonomy(
		'stage',
		array( 'post' ),
		array(
			'labels'            => array(
				'name'          => __( 'Stages', 'life-stages' ),
				'singular_name' => __( 'Stage', 'life-stages' ),
				'menu_name'     => __( 'Stages', 'life-stages' ),
				'all_items'     => __( 'All stages', 'life-stages' ),
				'edit_item'     => __( 'Edit stage', 'life-stages' ),
				'add_new_item'  => __( 'Add new stage', 'life-stages' ),
			),
			'description'       => __( 'Who an article is for. A post can belong to more than one stage.', 'life-stages' ),
			'hierarchical'      => true, // Checkbox UI in the editor.
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'stage' ),
		)
	);

	register_post_meta(
		'post',
		'ls_path_order',
		array(
			'type'              => 'integer',
			'single'            => true,
			'default'           => 0,
			'show_in_rest'      => true,
			'sanitize_callback' => 'absint',
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
		)
	);
}
add_action( 'init', 'ls_register' );

/**
 * Meta box so editors can set a post's place in its stage's learning path.
 */
function ls_add_meta_box() {
	add_meta_box( 'ls-path', __( 'Learning path', 'life-stages' ), 'ls_render_meta_box', 'post', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'ls_add_meta_box' );

/**
 * Render the learning-path meta box.
 *
 * @param WP_Post $post Current post.
 */
function ls_render_meta_box( $post ) {
	wp_nonce_field( 'ls_path_save', 'ls_path_nonce' );
	$val = (int) get_post_meta( $post->ID, 'ls_path_order', true );
	echo '<p><label for="ls_path_order">' . esc_html__( 'Position in “Read these first” (1 = first, 0 = not in the path)', 'life-stages' ) . '</label></p>';
	echo '<input type="number" min="0" step="1" id="ls_path_order" name="ls_path_order" value="' . esc_attr( $val ) . '" style="width:100%">';
}

/**
 * Save the learning-path position.
 *
 * @param int $post_id Post ID.
 */
function ls_save_meta_box( $post_id ) {
	if ( ! isset( $_POST['ls_path_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ls_path_nonce'] ) ), 'ls_path_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['ls_path_order'] ) ) {
		update_post_meta( $post_id, 'ls_path_order', absint( $_POST['ls_path_order'] ) );
	}
}
add_action( 'save_post_post', 'ls_save_meta_box' );

/* -------------------------------------------------------------------------
 * Helpers
 * ---------------------------------------------------------------------- */

/**
 * The primary (earliest) stage slug of a post.
 *
 * @param int $post_id Post ID.
 * @return string Empty if none.
 */
function ls_primary_stage( $post_id ) {
	$terms = get_the_terms( $post_id, 'stage' );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return '';
	}
	$slugs = wp_list_pluck( $terms, 'slug' );
	foreach ( array_keys( ls_stages() ) as $slug ) {
		if ( in_array( $slug, $slugs, true ) ) {
			return $slug;
		}
	}
	return $terms[0]->slug;
}

/**
 * Posts in a stage's learning path, in order.
 *
 * @param string $stage Stage slug.
 * @return WP_Post[]
 */
function ls_path_posts( $stage ) {
	$q = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 20,
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
			'meta_key'            => 'ls_path_order', // phpcs:ignore WordPress.DB.SlowDBQuery
			'orderby'             => array( 'meta_value_num' => 'ASC', 'date' => 'ASC' ),
			'meta_query'          => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'key'     => 'ls_path_order',
					'value'   => 0,
					'compare' => '>',
					'type'    => 'NUMERIC',
				),
			),
			'tax_query'           => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'taxonomy' => 'stage',
					'field'    => 'slug',
					'terms'    => $stage,
				),
			),
		)
	);
	return $q->posts;
}

/**
 * URL of a page by path with a fallback.
 *
 * @param string $path Page path.
 * @return string
 */
function ls_page_url( $path ) {
	$page = get_page_by_path( $path );
	return $page ? get_permalink( $page ) : home_url( '/' . $path . '/' );
}

/* -------------------------------------------------------------------------
 * Front-end: body class + "Next step in your stage"
 * ---------------------------------------------------------------------- */

/**
 * Add stage-{slug} to the body on single posts and stage pages, so the theme
 * can colour the page in that stage's accent.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function ls_body_class( $classes ) {
	if ( is_singular( 'post' ) ) {
		$stage = ls_primary_stage( get_queried_object_id() );
		if ( $stage ) {
			$classes[] = 'stage-' . $stage;
		}
	} elseif ( is_tax( 'stage' ) ) {
		$classes[] = 'stage-' . get_queried_object()->slug;
	} elseif ( is_page() ) {
		$slug = get_post_field( 'post_name', get_queried_object_id() );
		if ( array_key_exists( $slug, ls_stages() ) ) {
			$classes[] = 'stage-' . $slug;
		}
	}
	return $classes;
}
add_filter( 'body_class', 'ls_body_class' );

/**
 * Append the "Next step in your stage" box to single posts.
 *
 * @param string $content Post content.
 * @return string
 */
function ls_next_step( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	$post_id = get_the_ID();
	$stage   = ls_primary_stage( $post_id );
	if ( ! $stage ) {
		return $content;
	}
	$stages = ls_stages();
	$label  = isset( $stages[ $stage ] ) ? $stages[ $stage ]['label'] : $stage;
	$path   = ls_path_posts( $stage );
	$ids    = wp_list_pluck( $path, 'ID' );
	$pos    = array_search( $post_id, $ids, true );
	$next   = null;

	if ( false !== $pos && isset( $path[ $pos + 1 ] ) ) {
		$next = $path[ $pos + 1 ];
	} elseif ( false === $pos && ! empty( $path ) ) {
		$next = $path[0];
	}

	if ( $next ) {
		$step  = array_search( $next->ID, $ids, true ) + 1;
		$box   = '<aside class="ls-next stage-' . esc_attr( $stage ) . '" aria-label="' . esc_attr__( 'Next step', 'life-stages' ) . '">';
		$box  .= '<p class="ls-next-label">' . esc_html( sprintf( 'Next step in %1$s · %2$d of %3$d', $label, $step, count( $path ) ) ) . '</p>';
		$box  .= '<p class="ls-next-title"><a href="' . esc_url( get_permalink( $next ) ) . '">' . esc_html( get_the_title( $next ) ) . ' →</a></p>';
		$box  .= '</aside>';
		return $content . $box;
	}

	// End of the path: point to the next enabled stage, or back to this stage's hub.
	$next_stage = isset( $stages[ $stage ] ) ? $stages[ $stage ]['next'] : '';
	if ( $next_stage ) {
		$title = sprintf( 'Ready for more? Move up to %s →', $stages[ $next_stage ]['label'] );
		$url   = ls_page_url( $stages[ $next_stage ]['hub'] );
		$hint  = sprintf( 'You’ve finished the %s path.', $label );
	} else {
		$title = sprintf( 'Browse every %s article →', $label );
		$url   = ls_page_url( $stage );
		$hint  = 'You’ve finished the learning path.';
	}
	$box  = '<aside class="ls-next stage-' . esc_attr( $stage ) . '">';
	$box .= '<p class="ls-next-label">' . esc_html( $hint ) . '</p>';
	$box .= '<p class="ls-next-title"><a href="' . esc_url( $url ) . '">' . esc_html( $title ) . '</a></p>';
	$box .= '</aside>';
	return $content . $box;
}
add_filter( 'the_content', 'ls_next_step', 20 );

/* -------------------------------------------------------------------------
 * Shortcodes
 * ---------------------------------------------------------------------- */

/**
 * [ls_path stage="starting-out"] – the ordered "Read these first" list.
 *
 * @param array $atts Attributes.
 * @return string
 */
function ls_path_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'stage' => 'starting-out' ), $atts, 'ls_path' );
	if ( ! ls_stage_enabled( sanitize_key( $atts['stage'] ) ) ) {
		return '';
	}
	$posts = ls_path_posts( sanitize_key( $atts['stage'] ) );
	if ( empty( $posts ) ) {
		return '<p>' . esc_html__( 'Articles for this stage are on their way.', 'life-stages' ) . '</p>';
	}
	$out = '<ol class="ls-path stage-' . esc_attr( $atts['stage'] ) . '">';
	foreach ( $posts as $p ) {
		$excerpt = has_excerpt( $p ) ? get_the_excerpt( $p ) : '';
		$out    .= '<li><div><a href="' . esc_url( get_permalink( $p ) ) . '">' . esc_html( get_the_title( $p ) ) . '</a>';
		if ( $excerpt ) {
			$out .= '<span class="ls-path-excerpt">' . esc_html( wp_trim_words( $excerpt, 18 ) ) . '</span>';
		}
		$out .= '</div></li>';
	}
	return $out . '</ol>';
}
add_shortcode( 'ls_path', 'ls_path_shortcode' );

/**
 * [ls_signup stage="" title="" text="" button=""] – newsletter form that posts
 * to the form URL configured for that stage (Settings → Life Stages).
 *
 * @param array $atts Attributes.
 * @return string
 */
function ls_signup_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'stage'  => 'starting-out',
			'title'  => 'Get the weekly email',
			'text'   => 'One practical money lesson a week. No spam, no stock tips.',
			'button' => 'Subscribe',
		),
		$atts,
		'ls_signup'
	);

	$stage  = sanitize_key( $atts['stage'] );
	if ( ! ls_stage_enabled( $stage ) ) {
		return '';
	}
	$opts   = get_option( 'ls_settings', array() );
	$action = isset( $opts[ 'form_' . str_replace( '-', '_', $stage ) ] ) ? $opts[ 'form_' . str_replace( '-', '_', $stage ) ] : '';
	$field  = ! empty( $opts['email_field'] ) ? $opts['email_field'] : 'email_address';
	$id     = 'ls-email-' . wp_unique_id();

	wp_enqueue_script( 'ls-front', LS_URL . 'assets/front.js', array(), LS_VERSION, true );

	$out  = '<div class="ls-signup stage-' . esc_attr( $stage ) . '">';
	$out .= '<h3>' . esc_html( $atts['title'] ) . '</h3>';
	$out .= '<p>' . esc_html( $atts['text'] ) . '</p>';
	$out .= '<form method="post" action="' . esc_url( $action ? $action : '#' ) . '" data-ls-demo="' . ( $action ? '0' : '1' ) . '">';
	$out .= '<label for="' . esc_attr( $id ) . '">' . esc_html__( 'Email address', 'life-stages' ) . '</label>';
	$out .= '<input type="email" id="' . esc_attr( $id ) . '" name="' . esc_attr( $field ) . '" placeholder="you@example.com" required autocomplete="email">';
	$out .= '<input type="hidden" name="fields[stage]" value="' . esc_attr( $stage ) . '">';
	$out .= '<button type="submit">' . esc_html( $atts['button'] ) . '</button>';
	$out .= '</form>';
	$out .= '<p class="ls-signup-note">Unsubscribe any time. Moved to a new stage of life? You can update it from any email.</p>';
	$out .= '</div>';
	return $out;
}
add_shortcode( 'ls_signup', 'ls_signup_shortcode' );

/**
 * [ls_tools] – SIP, inflation and 60/40 rebalancing calculators.
 *
 * @return string
 */
function ls_tools_shortcode() {
	wp_enqueue_script( 'ls-front', LS_URL . 'assets/front.js', array(), LS_VERSION, true );
	ob_start();
	include LS_DIR . 'includes/tools.php';
	return ob_get_clean();
}
add_shortcode( 'ls_tools', 'ls_tools_shortcode' );

/* -------------------------------------------------------------------------
 * Settings → Life Stages
 * ---------------------------------------------------------------------- */

/**
 * Register settings.
 */
function ls_settings_init() {
	register_setting(
		'ls_settings_group',
		'ls_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'ls_sanitize_settings',
			'default'           => array(),
		)
	);
}
add_action( 'admin_init', 'ls_settings_init' );

/**
 * Sanitize settings.
 *
 * @param array $in Raw input.
 * @return array
 */
function ls_sanitize_settings( $in ) {
	$out = array();
	foreach ( array( 'form_starting_out', 'form_growing', 'form_building_wealth' ) as $k ) {
		$out[ $k ] = isset( $in[ $k ] ) ? esc_url_raw( $in[ $k ] ) : '';
	}
	$out['email_field'] = isset( $in['email_field'] ) ? sanitize_key( $in['email_field'] ) : 'email_address';
	$out['stages']      = isset( $in['stages'] ) && is_array( $in['stages'] ) ? array_values( array_intersect( array_keys( ls_all_stages() ), array_map( 'sanitize_key', $in['stages'] ) ) ) : array();
	if ( empty( $out['stages'] ) ) {
		$out['stages'] = array( 'starting-out' ); // At least one stage must stay on.
	}
	return $out;
}

/**
 * Add the settings page.
 */
function ls_settings_menu() {
	add_options_page( 'Life Stages', 'Life Stages', 'manage_options', 'life-stages', 'ls_settings_page' );
}
add_action( 'admin_menu', 'ls_settings_menu' );

/**
 * Render the settings page.
 */
function ls_settings_page() {
	$o      = wp_parse_args( get_option( 'ls_settings', array() ), array( 'form_starting_out' => '', 'form_growing' => '', 'form_building_wealth' => '', 'email_field' => 'email_address' ) );
	$fields = array(
		'form_starting_out'    => 'Starting Out form URL (e.g. First Job Budget Checklist)',
		'form_growing'         => 'Growing form URL (e.g. SIP Step-Up Planner)',
		'form_building_wealth' => 'Building Wealth form URL (e.g. 60/40 Rebalancing Worksheet)',
	);
	?>
	<div class="wrap">
		<h1>Life Stages</h1>
		<p>Each stage’s sign-up form posts to its own newsletter form, so new subscribers get the right tag automatically. In Kit, create one form per lead magnet, set it to add the matching stage tag, and paste its form URL (the <code>action</code> of its HTML embed, like <code>https://app.kit.com/forms/1234567/subscriptions</code>) below. Leave a field empty to keep the form in demo mode.</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'ls_settings_group' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row">Stages shown on the site</th>
					<td><fieldset>
						<?php foreach ( ls_all_stages() as $slug => $st ) : ?>
						<label style="display:block;margin-bottom:6px"><input type="checkbox" name="ls_settings[stages][]" value="<?php echo esc_attr( $slug ); ?>" <?php checked( ls_stage_enabled( $slug ) ); ?>> <strong><?php echo esc_html( $st['label'] ); ?></strong> · <?php echo esc_html( $st['who'] ); ?></label>
						<?php endforeach; ?>
						<p class="description">A stage that is switched off disappears everywhere: homepage card, menu, footer, its hub page, its articles and its sign-up form. Nothing is deleted, so you can switch it back on at any time (for example, when you launch a premium tier).</p>
					</fieldset></td>
				</tr>
				<?php foreach ( $fields as $k => $label ) : ?>
				<tr>
					<th scope="row"><label for="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $label ); ?></label></th>
					<td><input type="url" class="regular-text" id="<?php echo esc_attr( $k ); ?>" name="ls_settings[<?php echo esc_attr( $k ); ?>]" value="<?php echo esc_attr( $o[ $k ] ); ?>"></td>
				</tr>
				<?php endforeach; ?>
				<tr>
					<th scope="row"><label for="email_field">Email field name</label></th>
					<td><input type="text" class="regular-text" id="email_field" name="ls_settings[email_field]" value="<?php echo esc_attr( $o['email_field'] ); ?>">
					<p class="description">Kit uses <code>email_address</code>. Check your provider’s embed code if you use another service.</p></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
		<hr>
		<h2>Sample content</h2>
		<p>Creates the demo pages, articles, categories, stages and header menu. Existing items with the same slugs are skipped, so it is safe to run twice.</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="ls_import_demo">
			<?php wp_nonce_field( 'ls_import_demo' ); ?>
			<?php submit_button( 'Import sample content', 'secondary' ); ?>
		</form>
	</div>
	<?php
}

/* -------------------------------------------------------------------------
 * Sample content import (button, admin notice, WP-CLI)
 * ---------------------------------------------------------------------- */

require_once LS_DIR . 'includes/visibility.php';
require_once LS_DIR . 'includes/comments.php';
require_once LS_DIR . 'includes/demo-content.php';

/**
 * Handle the import button.
 */
function ls_handle_import() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Sorry, you are not allowed to do that.', 'life-stages' ) );
	}
	check_admin_referer( 'ls_import_demo' );
	$report = ls_import_demo_content();
	update_option( 'ls_demo_dismissed', 1 );
	set_transient( 'ls_import_report', $report, 60 );
	wp_safe_redirect( admin_url( 'options-general.php?page=life-stages&ls_imported=1' ) );
	exit;
}
add_action( 'admin_post_ls_import_demo', 'ls_handle_import' );

/**
 * Admin notices: offer the import once, then report results.
 */
function ls_admin_notices() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( isset( $_GET['ls_imported'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		$r = get_transient( 'ls_import_report' );
		echo '<div class="notice notice-success is-dismissible"><p>Sample content imported' . ( $r ? ': ' . esc_html( $r ) : '' ) . '. <a href="' . esc_url( home_url( '/' ) ) . '">View site</a></p></div>';
		return;
	}
	if ( get_option( 'ls_demo_dismissed' ) ) {
		return;
	}
	$url = admin_url( 'options-general.php?page=life-stages' );
	echo '<div class="notice notice-info"><p><strong>Life Stages is active.</strong> Want the sample pages, articles and menu? <a class="button button-primary" href="' . esc_url( $url ) . '">Set up sample content</a> <a href="' . esc_url( wp_nonce_url( add_query_arg( 'ls_dismiss', 1 ), 'ls_dismiss' ) ) . '">No thanks</a></p></div>';
}
add_action( 'admin_notices', 'ls_admin_notices' );

/**
 * Dismiss the import notice.
 */
function ls_maybe_dismiss() {
	if ( isset( $_GET['ls_dismiss'], $_GET['_wpnonce'] ) && current_user_can( 'manage_options' ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'ls_dismiss' ) ) {
		update_option( 'ls_demo_dismissed', 1 );
	}
}
add_action( 'admin_init', 'ls_maybe_dismiss' );

/**
 * Activation: register taxonomy and flush rewrites.
 */
function ls_activate() {
	ls_register();
	if ( ! get_option( 'ls_comments_configured' ) ) {
		ls_apply_comment_defaults();
		update_option( 'ls_comments_configured', 1 );
	}
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ls_activate' );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		'life-stages import-demo',
		function () {
			WP_CLI::success( ls_import_demo_content() );
		}
	);
}

/**
 * Shortcode blocks placed in theme templates or patterns are not run through
 * the_content, so process them here. Already-processed output is unaffected.
 *
 * @param string $content Rendered block.
 * @return string
 */
function ls_render_shortcode_block( $content ) {
	if ( false === strpos( $content, '[' ) ) {
		return $content;
	}
	// The core block wraps its text in <p>; our shortcodes output block-level HTML.
	if ( preg_match( '#^\s*<p>\s*(\[ls_[^\]]*\])\s*</p>\s*$#s', $content, $m ) ) {
		$content = $m[1];
	}
	return do_shortcode( $content );
}
add_filter( 'render_block_core/shortcode', 'ls_render_shortcode_block' );
