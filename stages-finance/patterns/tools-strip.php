<?php
/**
 * Title: Popular tools
 * Slug: stages-finance/tools-strip
 * Categories: stages-finance
 */
$tools = stages_finance_page_url( 'tools' );
$items = array(
	array( 'sip', 'SIP growth calculator', 'See what a monthly SIP could grow into over 10, 20 or 30 years.' ),
	array( 'inflation', 'Inflation calculator', 'Find out what today’s rupees will really buy in the future.' ),
	array( 'rebalance', '60/40 rebalancing calculator', 'Work out exactly how much to move to get back to your target mix.' ),
);
?>
<!-- wp:group {"tagName":"section","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--30)">
	<!-- wp:group {"align":"wide","className":"section-head","layout":{"type":"flex","orientation":"vertical"},"style":{"spacing":{"blockGap":"6px"}}} -->
	<div class="wp-block-group alignwide section-head">
		<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">Free tools</p><!-- /wp:paragraph -->
		<!-- wp:heading --><h2 class="wp-block-heading">Run your own numbers</h2><!-- /wp:heading -->
	</div>
	<!-- /wp:group -->
	<!-- wp:columns {"align":"wide","className":"feature-cols","style":{"spacing":{"blockGap":{"left":"20px","top":"20px"}}}} -->
	<div class="wp-block-columns alignwide feature-cols">
		<?php foreach ( $items as $it ) : ?>
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"feature-card","style":{"spacing":{"blockGap":"8px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
			<div class="wp-block-group feature-card">
				<!-- wp:heading {"level":3} --><h3 class="wp-block-heading"><a href="<?php echo esc_url( $tools . '#' . $it[0] ); ?>"><?php echo esc_html( $it[1] ); ?></a></h3><!-- /wp:heading -->
				<!-- wp:paragraph --><p><?php echo esc_html( $it[2] ); ?></p><!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<?php endforeach; ?>
	</div>
	<!-- /wp:columns -->
</section>
<!-- /wp:group -->
