<?php
/**
 * Hide switched-off stages everywhere on the front end, and the small
 * shortcodes that keep stage-aware page text correct.
 *
 * Nothing is deleted: switching a stage back on restores everything.
 *
 * @package life-stages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hub page IDs of switched-off stages.
 *
 * @return int[]
 */
function ls_disabled_hub_ids() {
	static $cache = null;
	if ( null !== $cache && did_action( 'wp' ) ) {
		return $cache;
	}
	$ids = array();
	$all = ls_all_stages();
	foreach ( ls_disabled_stage_slugs() as $slug ) {
		$page = get_page_by_path( $all[ $slug ]['hub'] );
		if ( $page ) {
			$ids[] = (int) $page->ID;
		}
	}
	$cache = $ids;
	return $ids;
}

/**
 * Tax query clause that removes posts in switched-off stages.
 *
 * @return array|null
 */
function ls_disabled_tax_clause() {
	$off = ls_disabled_stage_slugs();
	if ( empty( $off ) ) {
		return null;
	}
	return array(
		'taxonomy' => 'stage',
		'field'    => 'slug',
		'terms'    => $off,
		'operator' => 'NOT IN',
	);
}

/**
 * Remove posts and hub pages of switched-off stages from every front-end query
 * (homepage, archives, search, query loops, feeds and the learning path).
 *
 * @param WP_Query $q Query.
 */
function ls_hide_disabled_stages( $q ) {
	if ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST && current_user_can( 'edit_posts' ) ) ) {
		return;
	}
	$clause = ls_disabled_tax_clause();
	if ( ! $clause ) {
		return;
	}
	// Editors can still open a hidden article or hub page directly to work on it.
	if ( $q->is_main_query() && $q->is_singular() && current_user_can( 'edit_posts' ) ) {
		return;
	}
	$post_type = $q->get( 'post_type' );
	if ( '' === $post_type || 'post' === $post_type || 'any' === $post_type || ( is_array( $post_type ) && in_array( 'post', $post_type, true ) ) ) {
		$tax   = (array) $q->get( 'tax_query' );
		$tax[] = $clause;
		$q->set( 'tax_query', $tax );
	}
	$hubs = ls_disabled_hub_ids();
	if ( $hubs ) {
		$q->set( 'post__not_in', array_merge( (array) $q->get( 'post__not_in' ), $hubs ) );
	}
}
add_action( 'pre_get_posts', 'ls_hide_disabled_stages' );

/**
 * Direct visits to a switched-off stage's hub page, stage archive or articles get a 404.
 * Logged-in editors can still preview them.
 */
function ls_404_disabled_stages() {
	if ( current_user_can( 'edit_posts' ) || ! ls_disabled_stage_slugs() ) {
		return;
	}
	$hide = false;
	if ( is_tax( 'stage' ) ) {
		$hide = ! ls_stage_enabled( get_queried_object()->slug );
	} elseif ( is_page() ) {
		$hide = in_array( get_queried_object_id(), ls_disabled_hub_ids(), true );
	} elseif ( is_singular( 'post' ) ) {
		$terms = wp_get_object_terms( get_queried_object_id(), 'stage' ); // Unfiltered.
		if ( $terms && ! is_wp_error( $terms ) ) {
			$on   = array_intersect( wp_list_pluck( $terms, 'slug' ), ls_enabled_stage_slugs() );
			$hide = empty( $on );
		}
	}
	if ( $hide ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
	}
}
add_action( 'template_redirect', 'ls_404_disabled_stages', 1 );

/**
 * Hide navigation links that point to a switched-off stage's hub page.
 *
 * @param string $html  Rendered link.
 * @param array  $block Block.
 * @return string
 */
function ls_hide_disabled_nav_links( $html, $block ) {
	$hubs = ls_disabled_hub_ids();
	if ( ! $hubs ) {
		return $html;
	}
	$id = isset( $block['attrs']['id'] ) ? (int) $block['attrs']['id'] : 0;
	if ( $id && in_array( $id, $hubs, true ) && ( empty( $block['attrs']['kind'] ) || 'post-type' === $block['attrs']['kind'] ) ) {
		return '';
	}
	// A topic whose articles all belong to switched-off stages would show an empty page.
	if ( $id && isset( $block['attrs']['kind'], $block['attrs']['type'] ) && 'taxonomy' === $block['attrs']['kind'] && 'category' === $block['attrs']['type'] ) {
		$visible = new WP_Query(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'cat'            => $id,
			)
		);
		if ( ! $visible->have_posts() ) {
			return '';
		}
	}
	return $html;
}
add_filter( 'render_block_core/navigation-link', 'ls_hide_disabled_nav_links', 10, 2 );

/**
 * Hide stage-term links (badges) for switched-off stages.
 *
 * @param WP_Term[] $terms Terms.
 * @param int       $post_id Post.
 * @param string    $taxonomy Taxonomy.
 * @return WP_Term[]
 */
function ls_filter_terms( $terms, $post_id, $taxonomy ) {
	if ( 'stage' !== $taxonomy || is_admin() || ! is_array( $terms ) ) {
		return $terms;
	}
	return array_values(
		array_filter(
			$terms,
			function ( $t ) {
				return ls_stage_enabled( $t->slug );
			}
		)
	);
}
add_filter( 'get_the_terms', 'ls_filter_terms', 10, 3 );

/**
 * Keep switched-off stages out of the XML sitemap.
 *
 * @param array  $args      Query args.
 * @param string $post_type Post type.
 * @return array
 */
function ls_sitemap_posts( $args, $post_type ) {
	if ( 'post' === $post_type && ls_disabled_tax_clause() ) {
		$args['tax_query'] = array( ls_disabled_tax_clause() ); // phpcs:ignore WordPress.DB.SlowDBQuery
	}
	if ( 'page' === $post_type && ls_disabled_hub_ids() ) {
		$args['post__not_in'] = array_merge( isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array(), ls_disabled_hub_ids() );
	}
	return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'ls_sitemap_posts', 10, 2 );

/**
 * Keep switched-off stage archives out of the XML sitemap.
 *
 * @param array  $args     Query args.
 * @param string $taxonomy Taxonomy.
 * @return array
 */
function ls_sitemap_terms( $args, $taxonomy ) {
	if ( 'stage' === $taxonomy && ls_disabled_stage_slugs() ) {
		$ids = array();
		foreach ( ls_disabled_stage_slugs() as $slug ) {
			$t = get_term_by( 'slug', $slug, 'stage' );
			if ( $t ) {
				$ids[] = (int) $t->term_id;
			}
		}
		$args['exclude'] = $ids;
	}
	return $args;
}
add_filter( 'wp_sitemaps_taxonomies_query_args', 'ls_sitemap_terms', 10, 2 );

/* -------------------------------------------------------------------------
 * Stage-aware shortcodes used in page text
 * ---------------------------------------------------------------------- */

/**
 * [ls_stage_step stage="growing"] → "Stage 2 of 2 · Growing" (as a paragraph).
 *
 * @param array $atts Attributes.
 * @return string
 */
function ls_stage_step_shortcode( $atts ) {
	$atts   = shortcode_atts( array( 'stage' => 'starting-out' ), $atts );
	$stages = ls_stages();
	$s      = isset( $stages[ $atts['stage'] ] ) ? $stages[ $atts['stage'] ] : null;
	if ( ! $s ) {
		return '';
	}
	$text = 1 === $s['total'] ? $s['label'] : sprintf( 'Stage %1$d of %2$d · %3$s', $s['step'], $s['total'], $s['label'] );
	return '<p class="eyebrow">' . esc_html( $text ) . '</p>';
}
add_shortcode( 'ls_stage_step', 'ls_stage_step_shortcode' );

/**
 * [ls_stage_who stage="growing"] → the audience title and description.
 *
 * @param array $atts Attributes.
 * @return string
 */
function ls_stage_who_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'stage' => 'starting-out' ), $atts );
	$all  = ls_all_stages();
	if ( ! isset( $all[ $atts['stage'] ] ) ) {
		return '';
	}
	return '<p class="stage-who"><strong>' . esc_html( $all[ $atts['stage'] ]['who'] ) . ':</strong> ' . esc_html( $all[ $atts['stage'] ]['about'] ) . '</p>';
}
add_shortcode( 'ls_stage_who', 'ls_stage_who_shortcode' );

/**
 * [ls_hub_next stage="growing"] → link to the next enabled stage, or to Start Here.
 *
 * @param array $atts Attributes.
 * @return string
 */
function ls_hub_next_shortcode( $atts ) {
	$atts   = shortcode_atts( array( 'stage' => 'starting-out' ), $atts );
	$stages = ls_stages();
	if ( ! isset( $stages[ $atts['stage'] ] ) ) {
		return '';
	}
	$next = $stages[ $atts['stage'] ]['next'];
	if ( $next ) {
		$text = sprintf( 'Ready for the next step? Move up to %s →', $stages[ $next ]['label'] );
		$url  = ls_page_url( $stages[ $next ]['hub'] );
	} else {
		$first = array_key_first( $stages );
		if ( $first === $atts['stage'] ) {
			return '';
		}
		$text = sprintf( 'Know someone just starting their career? Share the %s guide →', $stages[ $first ]['label'] );
		$url  = ls_page_url( $stages[ $first ]['hub'] );
	}
	return '<p class="hub-next"><a href="' . esc_url( $url ) . '">' . esc_html( $text ) . '</a></p>';
}
add_shortcode( 'ls_hub_next', 'ls_hub_next_shortcode' );

/**
 * [ls_stage_quiz] → "Still not sure?" list, one question per enabled stage.
 *
 * @return string
 */
function ls_stage_quiz_shortcode() {
	$questions = array(
		'starting-out'    => 'Are you in your final year of college, or in your first year of work?',
		'growing'         => 'Have you been working for one to five years, with savings that are starting to grow?',
		'building-wealth' => 'Do you already have a portfolio worth protecting and want to manage it with discipline?',
	);
	$out = '<ol class="wp-block-list">';
	foreach ( ls_stages() as $slug => $s ) {
		$out .= '<li><strong>' . esc_html( $questions[ $slug ] ) . '</strong> Go to <a href="' . esc_url( ls_page_url( $s['hub'] ) ) . '">' . esc_html( $s['label'] ) . '</a>.</li>';
	}
	return $out . '</ol>';
}
add_shortcode( 'ls_stage_quiz', 'ls_stage_quiz_shortcode' );
