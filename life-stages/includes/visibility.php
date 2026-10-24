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
 * Hub page IDs of stages that are fully off (a "coming soon" hub becomes a waitlist page instead).
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
	foreach ( array_keys( $all ) as $slug ) {
		if ( 'off' !== ls_stage_state( $slug ) ) {
			continue;
		}
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
	$id = isset( $block['attrs']['id'] ) ? (int) $block['attrs']['id'] : 0;
	if ( $id && in_array( $id, $hubs, true ) && ( empty( $block['attrs']['kind'] ) || 'post-type' === $block['attrs']['kind'] ) ) {
		return '';
	}
	// Label links to a "coming soon" stage's page.
	if ( $id && ( empty( $block['attrs']['kind'] ) || 'post-type' === $block['attrs']['kind'] ) ) {
		foreach ( ls_all_stages() as $slug => $st ) {
			if ( ls_stage_soon( $slug ) ) {
				$page = get_page_by_path( $st['hub'] );
				if ( $page && (int) $page->ID === $id ) {
					return preg_replace( '#(<span class="wp-block-navigation-item__label">)(.*?)(</span>)#s', '$1$2 <span class="soon-badge">Soon</span>$3', $html, 1 );
				}
			}
		}
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
	$st  = $all[ $atts['stage'] ];
	$out = '<p class="stage-who"><strong>' . esc_html( $st['who'] ) . ':</strong> ' . esc_html( $st['about'] ) . '</p>';
	if ( ! empty( $st['ideas'] ) ) {
		$out .= '<p class="stage-ideas"><strong>What you’ll learn:</strong> ' . esc_html( $st['ideas'] ) . ' <a href="' . esc_url( ls_page_url( 'about' ) . '#influences' ) . '">Who shaped these ideas →</a></p>';
	}
	return $out;
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
	} elseif ( ls_next_soon_stage( $atts['stage'] ) ) {
		$all  = ls_all_stages();
		$soon = ls_next_soon_stage( $atts['stage'] );
		$text = sprintf( 'Ready for more? %s is coming soon. Join the waitlist →', $all[ $soon ]['label'] );
		$url  = ls_page_url( $all[ $soon ]['hub'] ) . '#waitlist';
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
		'growing'         => 'Have you been working for a few years, and want a clear plan for how much goes into equity and debt?',
		'building-wealth' => 'Do you want to research individual businesses and own a few high-quality ones for the long term?',
	);
	$out = '<ol class="wp-block-list">';
	foreach ( ls_all_stages() as $slug => $s ) {
		if ( ls_stage_enabled( $slug ) ) {
			$out .= '<li><strong>' . esc_html( $questions[ $slug ] ) . '</strong> Go to <a href="' . esc_url( ls_page_url( $s['hub'] ) ) . '">' . esc_html( $s['label'] ) . '</a>.</li>';
		} elseif ( ls_stage_soon( $slug ) ) {
			$out .= '<li><strong>' . esc_html( $questions[ $slug ] ) . '</strong> ' . esc_html( $s['label'] ) . ' is coming soon. <a href="' . esc_url( ls_page_url( $s['hub'] ) . '#waitlist' ) . '">Join the waitlist</a>.</li>';
		}
	}
	return $out . '</ol>';
}
add_shortcode( 'ls_stage_quiz', 'ls_stage_quiz_shortcode' );

/* -------------------------------------------------------------------------
 * "Coming soon" stage pages become a waitlist
 * ---------------------------------------------------------------------- */

/**
 * The stage slug whose hub page is being viewed, if it is "coming soon".
 *
 * @return string
 */
function ls_current_soon_hub() {
	if ( ! is_page() ) {
		return '';
	}
	$id = get_queried_object_id();
	foreach ( ls_all_stages() as $slug => $st ) {
		if ( ls_stage_soon( $slug ) ) {
			$page = get_page_by_path( $st['hub'] );
			if ( $page && (int) $page->ID === (int) $id ) {
				return $slug;
			}
		}
	}
	return '';
}

/**
 * Waitlist page content for a "coming soon" stage, as block markup.
 *
 * @param string $slug Stage slug.
 * @return string
 */
function ls_waitlist_content( $slug ) {
	$st    = ls_all_stages()[ $slug ];
	$class = array(
		'starting-out'    => 'is-stage-starting',
		'growing'         => 'is-stage-growing',
		'building-wealth' => 'is-stage-building',
	);
	$c      = isset( $class[ $slug ] ) ? $class[ $slug ] : '';
	$covers = isset( $st['covers'] ) ? $st['covers'] : array();

	$out  = '<!-- wp:group {"align":"full","className":"hub-hero ' . $c . '","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"},"blockGap":"16px"}},"layout":{"type":"constrained","contentSize":"880px"}} --><div class="wp-block-group alignfull hub-hero ' . $c . '" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">';
	$out .= '<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">' . esc_html( $st['label'] ) . ' <span class="soon-badge">Coming soon</span></p><!-- /wp:paragraph -->';
	$out .= '<!-- wp:heading {"level":1,"fontSize":"huge"} --><h1 class="wp-block-heading has-huge-font-size">' . esc_html( $st['title'] ) . '</h1><!-- /wp:heading -->';
	$out .= '<!-- wp:paragraph {"className":"lead"} --><p class="lead">' . esc_html( $st['ideas'] ) . '</p><!-- /wp:paragraph -->';
	$out .= '<!-- wp:paragraph {"className":"stage-who"} --><p class="stage-who"><strong>' . esc_html( $st['who'] ) . ':</strong> ' . esc_html( $st['about'] ) . '</p><!-- /wp:paragraph -->';
	$out .= '</div><!-- /wp:group -->';

	$out .= '<!-- wp:group {"align":"full","className":"' . $c . '","style":{"spacing":{"padding":{"top":"var:preset|spacing|60"},"blockGap":"20px"}},"layout":{"type":"constrained"}} --><div class="wp-block-group alignfull ' . $c . '" style="padding-top:var(--wp--preset--spacing--60)">';
	if ( $covers ) {
		$out .= '<!-- wp:heading --><h2 class="wp-block-heading">What this stage will cover</h2><!-- /wp:heading -->';
		$out .= '<!-- wp:list {"className":"soon-covers"} --><ul class="wp-block-list soon-covers">';
		foreach ( $covers as $item ) {
			$out .= '<!-- wp:list-item --><li>' . esc_html( $item ) . '</li><!-- /wp:list-item -->';
		}
		$out .= '</ul><!-- /wp:list -->';
	}
	$out .= '<!-- wp:paragraph {"className":"is-muted"} --><p class="is-muted">It’s built for readers who have outgrown the basics and want to own a few great businesses for the long term. ';
	$out .= 'The ideas behind it come from investors and teachers I credit on the <a href="' . esc_url( ls_page_url( 'about' ) . '#influences' ) . '">About page</a>.</p><!-- /wp:paragraph -->';
	$out .= '</div><!-- /wp:group -->';

	$out .= '<!-- wp:group {"align":"full","anchor":"waitlist","style":{"spacing":{"padding":{"top":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} --><div id="waitlist" class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--50)">';
	$out .= '<!-- wp:shortcode -->[ls_signup stage="' . esc_attr( $slug ) . '"]<!-- /wp:shortcode -->';
	$first = ls_enabled_stage_slugs();
	if ( $first ) {
		$f    = ls_all_stages()[ end( $first ) ];
		$out .= '<!-- wp:paragraph {"className":"hub-next"} --><p class="hub-next">While you wait: <a href="' . esc_url( ls_page_url( $f['hub'] ) ) . '">read the ' . esc_html( $f['label'] ) . ' guides →</a></p><!-- /wp:paragraph -->';
	}
	$out .= '</div><!-- /wp:group -->';
	return $out;
}

/**
 * Swap a "coming soon" stage's hub page content for the waitlist.
 *
 * @param string $content Raw post content (before blocks are rendered).
 * @return string
 */
function ls_soon_hub_content( $content ) {
	if ( ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	$slug = ls_current_soon_hub();
	return $slug ? ls_waitlist_content( $slug ) : $content;
}
add_filter( 'the_content', 'ls_soon_hub_content', 1 );

/**
 * Title tag for the waitlist page.
 *
 * @param array $parts Title parts.
 * @return array
 */
function ls_soon_hub_title( $parts ) {
	$slug = ls_current_soon_hub();
	if ( $slug ) {
		$parts['title'] = ls_all_stages()[ $slug ]['label'] . ' (coming soon)';
	}
	return $parts;
}
add_filter( 'document_title_parts', 'ls_soon_hub_title' );
