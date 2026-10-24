<?php
/**
 * Sample content importer: stages, categories, articles, pages, menu, settings.
 *
 * @package life-stages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/blocks.php';

/**
 * Get or create a term.
 *
 * @param string $name     Name.
 * @param string $slug     Slug.
 * @param string $taxonomy Taxonomy.
 * @param int    $parent   Parent term id.
 * @param string $desc     Description.
 * @return int Term id (0 on failure).
 */
function ls_demo_term( $name, $slug, $taxonomy, $parent = 0, $desc = '' ) {
	$t = get_term_by( 'slug', $slug, $taxonomy );
	if ( $t ) {
		return (int) $t->term_id;
	}
	$r = wp_insert_term(
		$name,
		$taxonomy,
		array(
			'slug'        => $slug,
			'parent'      => $parent,
			'description' => $desc,
		)
	);
	return is_wp_error( $r ) ? 0 : (int) $r['term_id'];
}

/**
 * Hub page content for one stage.
 *
 * @param string $stage   Stage slug.
 * @param int    $term_id Stage term id.
 * @return string
 */
function ls_demo_hub_content( $stage, $term_id ) {
	$hubs = array(
		'starting-out'    => array(
			'class'   => 'is-stage-starting',
			'step'    => 'Stage 1 of 3 · Starting Out',
			'title'   => 'Your first salary, sorted.',
			'lead'    => 'Everything you need for your first year of work: decoding your payslip, choosing a tax regime, building the save-first habit and making your first investment without rookie mistakes.',
			'who'     => 'For final-year students and freshers in their first year of work.',
			'magnet'  => array( 'Get the First Job Budget Checklist', 'A one-page checklist for the week your first salary lands, plus one short money lesson every week.', 'Send me the checklist' ),
			'next'    => array( 'growing', 'Been working for a year or more? Move up to Growing →' ),
		),
		'growing'         => array(
			'class'   => 'is-stage-growing',
			'step'    => 'Stage 2 of 3 · Growing',
			'title'   => 'Earning well. Now make it work.',
			'lead'    => 'Your salary is growing. This stage is about making sure your wealth grows faster: stepping up SIPs, beating lifestyle creep and learning to judge a business like a value investor.',
			'who'     => 'For professionals with 1–5 years of work experience.',
			'magnet'  => array( 'Get the SIP Step-Up Planner', 'A simple sheet that shows how raising your SIP with every increment changes your future, plus a weekly lesson for your stage.', 'Send me the planner' ),
			'next'    => array( 'building-wealth', 'Building a serious portfolio? Move up to Building Wealth →' ),
		),
		'building-wealth' => array(
			'class'   => 'is-stage-building',
			'step'    => 'Stage 3 of 3 · Building Wealth',
			'title'   => 'Protect what you’ve built. Compound the rest.',
			'lead'    => 'You have real money invested now. This stage is about keeping it safe and growing: asset allocation, disciplined rebalancing and owning businesses with lasting moats.',
			'who'     => 'For professionals with 5+ years of experience and a growing portfolio.',
			'magnet'  => array( 'Get the 60/40 Rebalancing Worksheet', 'A step-by-step worksheet for your yearly portfolio check-up, plus a weekly lesson for your stage.', 'Send me the worksheet' ),
			'next'    => array( 'starting-out', 'Know someone just starting out? Share the Starting Out guide →' ),
		),
	);
	$h = $hubs[ $stage ];

	$out  = '<!-- wp:group {"align":"full","className":"hub-hero ' . $h['class'] . '","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"},"blockGap":"16px"}},"layout":{"type":"constrained","contentSize":"880px"}} -->' . "\n";
	$out .= '<div class="wp-block-group alignfull hub-hero ' . $h['class'] . '" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">';
	$out .= ls_sc( '[ls_stage_step stage="' . $stage . '"]' );
	$out .= '<!-- wp:heading {"level":1,"fontSize":"huge"} -->' . "\n" . '<h1 class="wp-block-heading has-huge-font-size">' . $h['title'] . "</h1>\n<!-- /wp:heading -->\n\n";
	$out .= ls_p( $h['lead'], 'lead' );
	$out .= ls_sc( '[ls_stage_who stage="' . $stage . '"]' );
	$out .= "</div>\n<!-- /wp:group -->\n\n";

	$out .= '<!-- wp:group {"align":"full","className":"' . $h['class'] . '","style":{"spacing":{"padding":{"top":"var:preset|spacing|60"},"blockGap":"20px"}},"layout":{"type":"constrained"}} -->' . "\n";
	$out .= '<div class="wp-block-group alignfull ' . $h['class'] . '" style="padding-top:var(--wp--preset--spacing--60)">';
	$out .= ls_h( 'Read these first' );
	$out .= ls_p( 'A short learning path in the right order. Each article ends with a link to the next one.', 'is-muted' );
	$out .= ls_sc( '[ls_path stage="' . $stage . '"]' );
	$out .= "</div>\n<!-- /wp:group -->\n\n";

	$out .= '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60"},"blockGap":"20px"}},"layout":{"type":"constrained"}} -->' . "\n";
	$out .= '<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60)">';
	$out .= '<!-- wp:heading {"align":"wide"} -->' . "\n" . '<h2 class="wp-block-heading alignwide">Every article for this stage</h2>' . "\n<!-- /wp:heading -->\n\n";
	$out .= ls_query_grid( array( 'stage' => array( $term_id ) ), 9, 40 + $term_id );
	$out .= "</div>\n<!-- /wp:group -->\n\n";

	$out .= '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60"},"blockGap":"24px"}},"layout":{"type":"constrained"}} -->' . "\n";
	$out .= '<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60)">';
	$out .= ls_sc( '[ls_signup stage="' . $stage . '" title="' . $h['magnet'][0] . '" text="' . $h['magnet'][1] . '" button="' . $h['magnet'][2] . '"]' );
	$out .= ls_sc( '[ls_hub_next stage="' . $stage . '"]' );
	$out .= "</div>\n<!-- /wp:group -->\n\n";

	return $out;
}

/**
 * Static page content (other than hubs).
 *
 * @param int[] $vi_cat Value Investing category ids (parent and children).
 * @return array slug => [title, template, content]
 */
function ls_demo_pages( $vi_cat ) {
	$pages = array();

	$pages['articles'] = array(
		'All Articles',
		'',
		ls_p( 'Every guide, newest first. Or browse by your stage of life:', 'lead' )
		. ls_pattern( 'stages-finance/stage-chips' )
		. ls_query_grid( array(), 12, 51, true ),
	);

	$pages['start-here'] = array(
		'Start Here',
		'',
		ls_p( 'This blog is organised by stage of life, so you only see what’s useful right now. Pick the card that sounds most like you. You can switch any time.', 'lead' )
		. ls_pattern( 'stages-finance/stage-cards' )
		. ls_h( 'Still not sure? Find your stage' )
		. ls_sc( '[ls_stage_quiz]' )
		. ls_h( 'What makes this blog different' )
		. ls_list(
			array(
				'Plain language. Every term is explained with an everyday analogy.',
				'Principles, not predictions. No stock tips, no “hot” funds, no hype.',
				'Grounded in value investing, the approach of Benjamin Graham and Warren Buffett, adapted for today.',
				'Written by someone who has done it: 24 years in IT and 15 years of investing.',
			)
		)
		. ls_sc( '[ls_signup stage="starting-out" title="Get one practical money lesson a week" text="Free, five minutes to read, and matched to your stage of life." button="Subscribe"]' ),
	);

	$pages['value-investing'] = array(
		'Value Investing, Made Simple',
		'',
		ls_p( 'Value investing means buying a share of a good business for less than it’s really worth, then giving it time. It was laid out by Benjamin Graham in the 1930s and proven over decades by Warren Buffett. Four ideas sit at its heart.', 'lead' )
		. ls_h( '1. Intrinsic value: price is not the same as worth', 2, 'intrinsic-value' )
		. ls_analogy( '<strong>The iPhone launch vs the festive sale.</strong> The same phone costs much more on launch day than in the Diwali sale. The phone didn’t change; only the crowd’s excitement did. Shares work the same way.' )
		. ls_p( 'Graham imagined the market as “Mr. Market”, an emotional business partner who offers you a different price every day. You never have to accept his offer. <strong>Takeaway:</strong> before buying anything, ask what it’s actually worth, not just what it costs today.' )
		. ls_h( '2. Margin of safety: leave room to be wrong', 2, 'margin-of-safety' )
		. ls_analogy( '<strong>Engineers build a bridge for 30-tonne trucks when it only needs to carry 10.</strong> Their calculations might be off. Buying a ₹100 business at ₹70 gives you the same kind of cushion.' )
		. ls_p( '<strong>Takeaway:</strong> only buy when the price is comfortably below your estimate of value.' )
		. ls_h( '3. Economic moats: own what competitors can’t copy', 2, 'economic-moats' )
		. ls_analogy( '<strong>You don’t leave WhatsApp</strong> even though other apps exist, because everyone you know is there. That’s a moat. A business whose only edge is discounts has none.' )
		. ls_p( '<strong>Takeaway:</strong> the best long-term investments have durable advantages that protect their profits.' )
		. ls_h( '4. Circle of competence: invest in what you understand', 2, 'circle-of-competence' )
		. ls_analogy( '<strong>An IT professional can judge a software product in a week.</strong> Most of us can’t do that for a cement plant. Your work and daily life give you an edge in some industries and not in others.' )
		. ls_p( '<strong>Takeaway:</strong> stay inside your circle, and be honest about where its edges are.' )
		. ls_h( 'How it fits with a 60/40 portfolio' )
		. ls_p( 'Picking good businesses at fair prices is half the story. The other half is asset allocation: keeping roughly 60% in equity and 40% in debt, and rebalancing when markets move. Together they give you a margin of safety for your whole financial life, not just one stock.' )
		. '<!-- wp:heading {"align":"wide"} -->' . "\n" . '<h2 class="wp-block-heading alignwide">Value investing articles</h2>' . "\n<!-- /wp:heading -->\n\n"
		. ls_query_grid( array( 'category' => (array) $vi_cat ), 6, 61 )
		. ls_p( '<em>Everything here is educational. We don’t recommend specific stocks or funds.</em>', 'is-muted' ),
	);

	$pages['tools'] = array(
		'Free Calculators',
		'',
		ls_p( 'Three simple tools to run your own numbers. They work right here in your browser; nothing is stored or sent anywhere.', 'lead' )
		. ls_sc( '[ls_tools]' ),
	);

	$pages['about'] = array(
		'Our Philosophy',
		'',
		ls_p( 'Invest like a business owner, not a gambler. Then go live your life.', 'lead' )
		. ls_h( 'Who I am' )
		. ls_p( 'For 24 years, I worked in IT. I sat through the meetings, met the deadlines and lived the life many of you are living now. For 15 of those years, I was also quietly building wealth, not by watching screens or chasing tips, but by owning good businesses at sensible prices and sticking to a simple 60% equity / 40% debt plan. Along the way I watched brilliant colleagues lose their savings, and their sleep, to intraday trading, options and the crypto coin of the month. I started this blog because there is a calmer, proven path, and nobody showed it to them early enough.' )
		. ls_h( 'Why speculation fails busy professionals' )
		. ls_p( 'Trading promises fast money but usually delivers constant screen-checking, stress that leaks into your workday, and losses. SEBI’s own studies have found that roughly nine out of ten individual F&amp;O traders lose money. That isn’t bad luck. You’re competing against algorithms and full-time institutions while also trying to do your actual job. Speculation asks, “What will the price do next week?” Nobody can answer that reliably, least of all someone checking an app between meetings.' )
		. ls_h( 'The calmer path' )
		. ls_p( 'Value investing asks a better question: “What is this business worth, and am I paying a fair price for it?” Combined with disciplined asset allocation, it becomes a system that works quietly in the background while you focus on your career and your life. Benjamin Graham laid the foundation, Warren Buffett proved it over decades, and it applies just as well to today’s digital businesses.' )
		. ls_h( 'What you will never find here' )
		. ls_list(
			array(
				'Stock tips or “buy this now” calls.',
				'Promises of guaranteed or fast returns.',
				'Jargon without a plain-English explanation.',
				'Paid promotions disguised as advice.',
			)
		)
		. ls_p( '<em>This site is for education only. I am not a SEBI-registered investment adviser, and nothing here is a recommendation to buy or sell any security. Please consult a qualified professional before making financial decisions.</em>', 'is-muted' ),
	);

	$pages['subscribe'] = array(
		'Get the Weekly Email',
		'',
		ls_p( 'One short, practical money lesson every week, matched to your stage of life. Pick the free guide that fits you best and we’ll send the right emails from there.', 'lead' )
		. '<!-- wp:group {"align":"wide","className":"ls-signup-grid","layout":{"type":"default"}} -->' . "\n<div class=\"wp-block-group alignwide ls-signup-grid\">"
		. ls_sc( '[ls_signup stage="starting-out" title="Starting Out" text="The First Job Budget Checklist, plus lessons on payslips, tax and your first SIP." button="Get the checklist"]' )
		. ls_sc( '[ls_signup stage="growing" title="Growing" text="The SIP Step-Up Planner, plus lessons on lifestyle creep and judging a business." button="Get the planner"]' )
		. ls_sc( '[ls_signup stage="building-wealth" title="Building Wealth" text="The 60/40 Rebalancing Worksheet, plus lessons on allocation and moats." button="Get the worksheet"]' )
		. "</div>\n<!-- /wp:group -->\n\n"
		. ls_p( 'No spam and no stock tips. Unsubscribe with one click. You can change your stage from the footer of any email.', 'is-muted' ),
	);

	return $pages;
}

/**
 * Create or fetch a page.
 *
 * @param string $slug     Slug.
 * @param string $title    Title.
 * @param string $content  Content.
 * @param string $template Page template slug.
 * @param int    $order    Menu order.
 * @return int Page id.
 */
function ls_demo_page( $slug, $title, $content, $template = '', $order = 0 ) {
	$existing = get_page_by_path( $slug );
	if ( $existing ) {
		return (int) $existing->ID;
	}
	$id = wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
			'menu_order'   => $order,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		)
	);
	if ( $id && ! is_wp_error( $id ) && $template ) {
		update_post_meta( $id, '_wp_page_template', $template );
	}
	return is_wp_error( $id ) ? 0 : (int) $id;
}

/**
 * Replace {{url:slug}} placeholders with real page links.
 *
 * @param string $content Content.
 * @return string
 */
function ls_demo_fill_urls( $content ) {
	return preg_replace_callback(
		'/\{\{url:([a-z0-9-]+)\}\}/',
		function ( $m ) {
			return esc_url( ls_page_url( $m[1] ) );
		},
		$content
	);
}

/**
 * Build the header navigation menu.
 *
 * @param array $pages slug => id.
 * @param array $cats  slug => id.
 * @return int Navigation post id.
 */
function ls_demo_navigation( $pages, $cats ) {
	$existing = (int) get_option( 'ls_nav_id' );
	if ( $existing && get_post( $existing ) ) {
		return $existing;
	}

	$page_link = function ( $slug, $label, $class = '' ) use ( $pages ) {
		$attrs = array(
			'label' => $label,
			'type'  => 'page',
			'id'    => $pages[ $slug ],
			'url'   => get_permalink( $pages[ $slug ] ),
			'kind'  => 'post-type',
		);
		if ( $class ) {
			$attrs['className'] = $class;
		}
		return '<!-- wp:navigation-link ' . wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . ' /-->';
	};
	$cat_link = function ( $slug, $label, $class = '' ) use ( $cats ) {
		$attrs = array(
			'label' => $label,
			'type'  => 'category',
			'id'    => $cats[ $slug ],
			'url'   => get_term_link( (int) $cats[ $slug ], 'category' ),
			'kind'  => 'taxonomy',
		);
		if ( $class ) {
			$attrs['className'] = $class;
		}
		return '<!-- wp:navigation-link ' . wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . ' /-->';
	};

	$learn = array(
		'label' => 'Learn',
		'type'  => 'page',
		'id'    => $pages['articles'],
		'url'   => get_permalink( $pages['articles'] ),
		'kind'  => 'post-type',
	);

	$content  = $page_link( 'start-here', 'Start Here' );
	$content .= '<!-- wp:navigation-submenu ' . wp_json_encode( $learn, JSON_UNESCAPED_SLASHES ) . ' -->';
	$content .= $page_link( 'starting-out', 'Starting Out' );
	$content .= $page_link( 'growing', 'Growing' );
	$content .= $page_link( 'building-wealth', 'Building Wealth' );
	$content .= $cat_link( 'budgeting-salary', 'Budgeting & Salary', 'menu-divider' );
	$content .= $cat_link( 'taxes', 'Taxes' );
	$content .= $cat_link( 'investing-basics', 'Investing Basics' );
	$content .= $cat_link( 'portfolio-management', 'Portfolio Management' );
	$content .= $page_link( 'articles', 'All articles', 'menu-divider' );
	$content .= '<!-- /wp:navigation-submenu -->';
	$content .= $page_link( 'value-investing', 'Value Investing' );
	$content .= $page_link( 'tools', 'Tools' );
	$content .= $page_link( 'about', 'About' );

	$id = wp_insert_post(
		array(
			'post_type'    => 'wp_navigation',
			'post_status'  => 'publish',
			'post_title'   => 'Header menu',
			'post_content' => $content,
		)
	);
	if ( $id && ! is_wp_error( $id ) ) {
		update_option( 'ls_nav_id', $id );
		return (int) $id;
	}
	return 0;
}

/**
 * Run the full import. Safe to run more than once.
 *
 * @return string Short report.
 */
function ls_import_demo_content() {
	// Content is trusted, written by this plugin. Allow block markup to pass untouched.
	kses_remove_filters();
	ls_register();

	$author = get_current_user_id();
	if ( ! $author ) {
		$admins = get_users( array( 'role' => 'administrator', 'number' => 1, 'fields' => 'ID' ) );
		$author = $admins ? (int) $admins[0] : 1;
	}

	/* Comments: recommended discussion settings before any article is created. */
	ls_apply_comment_defaults();
	update_option( 'ls_comments_configured', 1 );

	/* Stages. */
	$stage_ids = array(
		'starting-out'    => ls_demo_term( 'Starting Out', 'starting-out', 'stage', 0, 'For final-year students and freshers: payslips, first budgets, tax basics and your first investments.' ),
		'growing'         => ls_demo_term( 'Growing', 'growing', 'stage', 0, 'For professionals with 1–5 years of experience: smarter SIPs, beating lifestyle creep and judging a business.' ),
		'building-wealth' => ls_demo_term( 'Building Wealth', 'building-wealth', 'stage', 0, 'For professionals with 5+ years of experience: asset allocation, rebalancing and economic moats.' ),
	);

	/* Topic categories (parent => children). */
	$tree = array(
		array( 'Budgeting & Salary', 'budgeting-salary', array( array( 'Payslip & CTC', 'payslip-ctc' ), array( 'Budgeting & Emergency Funds', 'budgeting-emergency-funds' ), array( 'Lifestyle & Spending', 'lifestyle-spending' ) ) ),
		array( 'Taxes', 'taxes', array( array( 'Tax Regimes & Filing', 'tax-regimes-filing' ), array( 'EPF, NPS & Retirement Accounts', 'epf-nps-retirement' ) ) ),
		array( 'Investing Basics', 'investing-basics', array( array( 'Mutual Funds & SIPs', 'mutual-funds-sips' ), array( 'Compounding & Inflation', 'compounding-inflation' ) ) ),
		array( 'Value Investing', 'value-investing', array( array( 'Intrinsic Value & Margin of Safety', 'intrinsic-value-margin-of-safety' ), array( 'Moats & Business Analysis', 'moats-business-analysis' ) ) ),
		array( 'Portfolio Management', 'portfolio-management', array( array( 'Asset Allocation', 'asset-allocation' ), array( 'Rebalancing', 'rebalancing' ) ) ),
		array( 'Insurance & Protection', 'insurance-protection', array() ),
	);
	$cat_ids = array();
	foreach ( $tree as $node ) {
		$pid                  = ls_demo_term( $node[0], $node[1], 'category' );
		$cat_ids[ $node[1] ]  = $pid;
		foreach ( $node[2] as $child ) {
			$cat_ids[ $child[1] ] = ls_demo_term( $child[0], $child[1], 'category', $pid );
		}
	}

	/* Pages. */
	$page_ids = array();
	$order    = 0;
	$vi_ids = array_values( array_intersect_key( $cat_ids, array_flip( array( 'value-investing', 'intrinsic-value-margin-of-safety', 'moats-business-analysis' ) ) ) );
	foreach ( ls_demo_pages( $vi_ids ) as $slug => $p ) {
		$page_ids[ $slug ] = ls_demo_page( $slug, $p[0], $p[2], $p[1], $order++ );
	}
	$hub_titles = array(
		'starting-out'    => 'Starting Out',
		'growing'         => 'Growing',
		'building-wealth' => 'Building Wealth',
	);
	foreach ( $hub_titles as $slug => $title ) {
		$page_ids[ $slug ] = ls_demo_page( $slug, $title, ls_demo_hub_content( $slug, $stage_ids[ $slug ] ), 'page-no-title', $order++ );
	}

	/* Permalinks first, so the links we write are pretty. */
	if ( ! get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}
	global $wp_rewrite;
	$wp_rewrite->init();
	$wp_rewrite->set_permalink_structure( get_option( 'permalink_structure' ) );

	/* Second pass: fill page-to-page links now that every page exists. */
	foreach ( $page_ids as $id ) {
		$post = get_post( $id );
		if ( $post && false !== strpos( $post->post_content, '{{url:' ) ) {
			wp_update_post(
				array(
					'ID'           => $id,
					'post_content' => ls_demo_fill_urls( $post->post_content ),
				)
			);
		}
	}

	/* Articles. */
	$created = 0;
	$posts   = include __DIR__ . '/demo-posts.php';
	foreach ( $posts as $a ) {
		if ( get_page_by_path( $a['slug'], OBJECT, 'post' ) ) {
			continue;
		}
		$date = gmdate( 'Y-m-d H:i:s', time() - ( $a['days'] * DAY_IN_SECONDS ) - 3 * HOUR_IN_SECONDS );
		$id   = wp_insert_post(
			array(
				'post_type'     => 'post',
				'post_status'   => 'publish',
				'post_title'    => $a['title'],
				'post_name'     => $a['slug'],
				'post_excerpt'  => $a['excerpt'],
				'comment_status' => 'open',
				'ping_status'   => 'closed',
				'post_content'  => ls_demo_fill_urls( $a['content'] ),
				'post_author'   => $author,
				'post_date_gmt' => $date,
				'post_date'     => get_date_from_gmt( $date ),
				'post_category' => array_map(
					function ( $s ) use ( $cat_ids ) {
						return $cat_ids[ $s ];
					},
					$a['cats']
				),
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			wp_set_object_terms( $id, $a['stages'], 'stage' );
			update_post_meta( $id, 'ls_path_order', (int) $a['order'] );
			++$created;
		}
	}

	/* Remove WordPress's untouched default post and page. */
	foreach ( array( array( 'hello-world', 'post' ), array( 'sample-page', 'page' ) ) as $default ) {
		$d = get_page_by_path( $default[0], OBJECT, $default[1] );
		if ( $d && $d->post_modified_gmt === $d->post_date_gmt ) {
			wp_trash_post( $d->ID );
		}
	}

	/* Menu + reading settings. */
	ls_demo_navigation( $page_ids, $cat_ids );
	// The theme's front-page template is the homepage, so no static front page is needed.
	update_option( 'show_on_front', 'posts' );
	update_option( 'page_on_front', 0 );
	update_option( 'page_for_posts', 0 );
	update_option( 'default_category', $cat_ids['investing-basics'] );

	update_option( 'ls_demo_dismissed', 1 );
	flush_rewrite_rules();
	kses_init_filters();

	return sprintf( '%d articles, %d pages, 3 stages and the header menu are ready', $created, count( $page_ids ) );
}
