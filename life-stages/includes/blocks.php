<?php
/**
 * Tiny helpers that build block markup for the sample content.
 *
 * @package life-stages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Paragraph.
 *
 * @param string $html  Inline HTML.
 * @param string $class Optional class.
 * @return string
 */
function ls_p( $html, $class = '' ) {
	if ( $class ) {
		return '<!-- wp:paragraph {"className":"' . $class . '"} -->' . "\n" . '<p class="' . $class . '">' . $html . '</p>' . "\n<!-- /wp:paragraph -->\n\n";
	}
	return "<!-- wp:paragraph -->\n<p>" . $html . "</p>\n<!-- /wp:paragraph -->\n\n";
}

/**
 * Heading.
 *
 * @param string $text   Heading text.
 * @param int    $level  Level.
 * @param string $anchor Optional anchor id.
 * @return string
 */
function ls_h( $text, $level = 2, $anchor = '' ) {
	$attrs = array();
	if ( 2 !== $level ) {
		$attrs['level'] = $level;
	}
	if ( $anchor ) {
		$attrs['anchor'] = $anchor;
	}
	$json = $attrs ? ' ' . wp_json_encode( $attrs ) : '';
	$id   = $anchor ? ' id="' . $anchor . '"' : '';
	return '<!-- wp:heading' . $json . " -->\n<h{$level} class=\"wp-block-heading\"{$id}>" . $text . "</h{$level}>\n<!-- /wp:heading -->\n\n";
}

/**
 * List.
 *
 * @param string[] $items   Items (inline HTML).
 * @param bool     $ordered Ordered list.
 * @return string
 */
function ls_list( $items, $ordered = false ) {
	$tag  = $ordered ? 'ol' : 'ul';
	$out  = '<!-- wp:list' . ( $ordered ? ' {"ordered":true}' : '' ) . " -->\n<{$tag} class=\"wp-block-list\">";
	foreach ( $items as $i ) {
		$out .= "<!-- wp:list-item -->\n<li>" . $i . "</li>\n<!-- /wp:list-item -->";
	}
	return $out . "</{$tag}>\n<!-- /wp:list -->\n\n";
}

/**
 * "Your one action" style callout.
 *
 * @param string $label Small label.
 * @param string $html  Body.
 * @return string
 */
function ls_callout( $label, $html ) {
	return '<!-- wp:group {"className":"callout","layout":{"type":"default"},"style":{"spacing":{"blockGap":"4px"}}} -->' . "\n"
		. '<div class="wp-block-group callout">'
		. ls_p( $label, 'callout-label' )
		. ls_p( $html )
		. "</div>\n<!-- /wp:group -->\n\n";
}

/**
 * Analogy box.
 *
 * @param string $html Body.
 * @return string
 */
function ls_analogy( $html ) {
	return '<!-- wp:group {"className":"analogy","layout":{"type":"default"}} -->' . "\n"
		. '<div class="wp-block-group analogy">'
		. ls_p( $html )
		. "</div>\n<!-- /wp:group -->\n\n";
}

/**
 * Table.
 *
 * @param string[]   $head Header cells.
 * @param string[][] $rows Body rows.
 * @return string
 */
function ls_table( $head, $rows ) {
	$out = "<!-- wp:table -->\n<figure class=\"wp-block-table\"><table><thead><tr>";
	foreach ( $head as $h ) {
		$out .= '<th>' . $h . '</th>';
	}
	$out .= '</tr></thead><tbody>';
	foreach ( $rows as $r ) {
		$out .= '<tr>';
		foreach ( $r as $c ) {
			$out .= '<td>' . $c . '</td>';
		}
		$out .= '</tr>';
	}
	return $out . "</tbody></table></figure>\n<!-- /wp:table -->\n\n";
}

/**
 * Shortcode block.
 *
 * @param string $code Shortcode text.
 * @return string
 */
function ls_sc( $code ) {
	return "<!-- wp:shortcode -->\n" . $code . "\n<!-- /wp:shortcode -->\n\n";
}

/**
 * Pattern reference block.
 *
 * @param string $slug Pattern slug.
 * @return string
 */
function ls_pattern( $slug ) {
	return '<!-- wp:pattern {"slug":"' . $slug . '"} /-->' . "\n\n";
}

/**
 * Query loop of post cards filtered by stage term or category.
 *
 * @param array $tax   e.g. ['stage' => [12]] or ['category' => [3]].
 * @param int   $count Posts per page.
 * @param int   $qid   Query id.
 * @param bool  $paged Add pagination.
 * @param string $empty Message when there are no posts.
 * @return string
 */
function ls_query_grid( $tax, $count = 9, $qid = 31, $paged = false, $empty = '' ) {
	$attrs = array(
		'queryId' => $qid,
		'query'   => array(
			'perPage'  => $count,
			'pages'    => 0,
			'offset'   => 0,
			'postType' => 'post',
			'order'    => 'desc',
			'orderBy'  => 'date',
			'inherit'  => false,
		),
		'align'   => 'wide',
	);
	if ( $tax ) {
		$attrs['query']['taxQuery'] = $tax;
	}
	$pager = $paged ? '<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"32px"}}}} --><!-- wp:query-pagination-previous /--><!-- wp:query-pagination-numbers /--><!-- wp:query-pagination-next /--><!-- /wp:query-pagination -->' : '';
	return '<!-- wp:query ' . wp_json_encode( $attrs ) . " -->\n<div class=\"wp-block-query alignwide\">"
		. '<!-- wp:post-template {"className":"post-grid","style":{"spacing":{"blockGap":"24px"}},"layout":{"type":"grid","columnCount":3}} -->'
		. '<!-- wp:post-terms {"term":"stage","className":"stage-badge"} /-->'
		. '<!-- wp:post-title {"level":3,"isLink":true} /-->'
		. '<!-- wp:post-excerpt {"excerptLength":22} /-->'
		. '<!-- wp:post-date /-->'
		. '<!-- /wp:post-template -->'
		. $pager
		. ( $empty ? '<!-- wp:query-no-results --><!-- wp:paragraph {"className":"is-muted"} --><p class="is-muted">' . $empty . '</p><!-- /wp:paragraph --><!-- /wp:query-no-results -->' : '' )
		. "</div>\n<!-- /wp:query -->\n\n";
}
