<?php
/**
 * Stages Finance theme functions.
 *
 * @package stages-finance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme setup: editor styles.
 */
function stages_finance_setup() {
	add_theme_support( 'wp-block-styles' );
	add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'stages_finance_setup' );

/**
 * Front-end stylesheet.
 */
function stages_finance_enqueue() {
	wp_enqueue_style(
		'stages-finance',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'stages_finance_enqueue' );

/**
 * URL of a page by its slug (path), falling back to a home-relative URL.
 * Keeps pattern links working with any permalink setting.
 *
 * @param string $path Page path, e.g. "starting-out".
 * @return string
 */
function stages_finance_page_url( $path ) {
	$page = get_page_by_path( $path );
	if ( $page ) {
		return get_permalink( $page );
	}
	return home_url( '/' . trim( $path, '/' ) . '/' );
}

/**
 * URL of a category by slug, with a safe fallback.
 *
 * @param string $slug Category slug.
 * @return string
 */
function stages_finance_category_url( $slug ) {
	$term = get_term_by( 'slug', $slug, 'category' );
	if ( $term && ! is_wp_error( $term ) ) {
		return get_term_link( $term );
	}
	return home_url( '/' );
}

/**
 * Pattern category for this theme's sections.
 */
function stages_finance_pattern_category() {
	register_block_pattern_category(
		'stages-finance',
		array( 'label' => __( 'Stages Finance', 'stages-finance' ) )
	);
}
add_action( 'init', 'stages_finance_pattern_category' );

/**
 * Inline stroke icons used by the patterns (decorative).
 *
 * @param string $name Icon name.
 * @return string SVG markup.
 */
function stages_finance_icon( $name ) {
	$paths = array(
		'cap'      => '<path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 2 9 2 12 0v-5"/>',
		'trend'    => '<path d="M3 17l6-6 4 4 8-8"/><path d="M15 7h6v6"/>',
		'pillars'  => '<path d="M3 21h18"/><path d="M4 10h16"/><path d="M12 3l9 5H3l9-5z"/><path d="M6 10v8M10 10v8M14 10v8M18 10v8"/>',
		'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'monitor'  => '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/>',
		'shield'   => '<path d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z"/>',
	);
	$d = isset( $paths[ $name ] ) ? $paths[ $name ] : '';
	return '<svg aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' . $d . '</svg>';
}

/**
 * The three stage cards (columns block markup).
 *
 * @param int $level Heading level for card titles.
 * @return string Block markup.
 */
function stages_finance_stage_cards_markup( $level = 2 ) {
	$cards = array(
		array(
			'class' => 'is-stage-starting',
			'icon'  => 'cap',
			'label' => 'Starting Out',
			'title' => 'Your first salary, sorted.',
			'text'  => 'Decode your payslip, beat the tax confusion and start investing the right way from day one.',
			'who'   => 'Final-year students &amp; freshers',
			'cta'   => 'Start my first-job plan',
			'url'   => stages_finance_page_url( 'starting-out' ),
		),
		array(
			'class' => 'is-stage-growing',
			'icon'  => 'trend',
			'label' => 'Growing',
			'title' => 'Earning well. Now make it work.',
			'text'  => 'Turn a growing salary into growing wealth, with smarter SIPs, your first stock analysis and no lifestyle creep.',
			'who'   => '1–5 years of work experience',
			'cta'   => 'Grow my money',
			'url'   => stages_finance_page_url( 'growing' ),
		),
		array(
			'class' => 'is-stage-building',
			'icon'  => 'pillars',
			'label' => 'Building Wealth',
			'title' => 'Protect what you’ve built. Compound the rest.',
			'text'  => 'Asset allocation, rebalancing and value investing for a portfolio that survives every market cycle.',
			'who'   => '5+ years of work experience',
			'cta'   => 'Build my portfolio',
			'url'   => stages_finance_page_url( 'building-wealth' ),
		),
	);

	$level = max( 2, min( 4, (int) $level ) );
	$out   = '<!-- wp:columns {"align":"wide","className":"stage-cards"} --><div class="wp-block-columns alignwide stage-cards">';
	foreach ( $cards as $c ) {
		$out .= '<!-- wp:column --><div class="wp-block-column">';
		$out .= '<!-- wp:group {"className":"stage-card ' . $c['class'] . '","style":{"spacing":{"blockGap":"14px"}},"layout":{"type":"flex","orientation":"vertical","verticalAlignment":"top"}} --><div class="wp-block-group stage-card ' . $c['class'] . '">';
		$out .= '<!-- wp:paragraph {"className":"eyebrow card-eyebrow"} --><p class="eyebrow card-eyebrow">' . $c['label'] . '</p><!-- /wp:paragraph -->';
		$out .= '<!-- wp:heading {"level":' . $level . '} --><h' . $level . ' class="wp-block-heading">' . $c['title'] . '</h' . $level . '><!-- /wp:heading -->';
		$out .= '<!-- wp:paragraph {"className":"card-text"} --><p class="card-text">' . $c['text'] . '</p><!-- /wp:paragraph -->';
		$out .= '<!-- wp:paragraph {"className":"card-who"} --><p class="card-who">' . $c['who'] . '</p><!-- /wp:paragraph -->';
		$out .= '<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $c['url'] ) . '">' . $c['cta'] . ' →</a></div><!-- /wp:button --></div><!-- /wp:buttons -->';
		$out .= '</div><!-- /wp:group -->';
		$out .= '</div><!-- /wp:column -->';
	}
	$out .= '</div><!-- /wp:columns -->';
	return $out;
}
