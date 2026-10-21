<?php
/**
 * Title: The Value Way (four principles)
 * Slug: stages-finance/value-way
 * Categories: stages-finance
 */
$vi       = stages_finance_page_url( 'value-investing' );
$items    = array(
	array( 'intrinsic-value', 'Intrinsic Value', 'Price is what you pay. Value is what you get. Learn to tell them apart.' ),
	array( 'margin-of-safety', 'Margin of Safety', 'Leave room to be wrong, the way engineers build bridges for heavier trucks.' ),
	array( 'economic-moats', 'Economic Moats', 'Own businesses that competitors can’t easily copy or attack.' ),
	array( 'circle-of-competence', 'Circle of Competence', 'Invest in what you understand. Your day job is an edge.' ),
);
?>
<!-- wp:group {"tagName":"section","className":"band-surface","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group band-surface" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:group {"align":"wide","className":"section-head","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
	<div class="wp-block-group alignwide section-head">
		<!-- wp:group {"layout":{"type":"flex","orientation":"vertical"},"style":{"spacing":{"blockGap":"6px"}}} -->
		<div class="wp-block-group">
			<!-- wp:paragraph {"className":"eyebrow"} --><p class="eyebrow">The Value Way</p><!-- /wp:paragraph -->
			<!-- wp:heading --><h2 class="wp-block-heading">Four ideas behind everything we teach</h2><!-- /wp:heading -->
		</div>
		<!-- /wp:group -->
		<!-- wp:paragraph --><p><a href="<?php echo esc_url( $vi ); ?>"><strong>Explore value investing →</strong></a></p><!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
	<!-- wp:columns {"align":"wide","className":"feature-cols","style":{"spacing":{"blockGap":{"left":"20px","top":"20px"}}}} -->
	<div class="wp-block-columns alignwide feature-cols">
		<?php foreach ( $items as $i => $it ) : ?>
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:group {"className":"feature-card","style":{"spacing":{"blockGap":"8px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
			<div class="wp-block-group feature-card">
				<!-- wp:paragraph {"className":"feature-num"} --><p class="feature-num">0<?php echo (int) $i + 1; ?></p><!-- /wp:paragraph -->
				<!-- wp:heading {"level":3} --><h3 class="wp-block-heading"><a href="<?php echo esc_url( $vi . '#' . $it[0] ); ?>"><?php echo esc_html( $it[1] ); ?></a></h3><!-- /wp:heading -->
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
