<?php
/**
 * Title: Site header
 * Slug: stages-finance/header
 * Categories: header
 * Block Types: core/template-part/header
 * Inserter: no
 */
?>
<!-- wp:group {"tagName":"header","className":"site-header","style":{"spacing":{"padding":{"top":"14px","bottom":"14px"}}},"layout":{"type":"constrained"}} -->
<header class="wp-block-group site-header" style="padding-top:14px;padding-bottom:14px">
	<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:site-title {"level":0} /-->
		<!-- wp:group {"style":{"spacing":{"blockGap":"28px"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
		<div class="wp-block-group">
			<!-- wp:navigation {"overlayMenu":"mobile","layout":{"type":"flex","justifyContent":"right"},"style":{"spacing":{"blockGap":"28px"}}} /-->
			<!-- wp:buttons {"className":"header-subscribe"} -->
			<div class="wp-block-buttons header-subscribe">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( stages_finance_page_url( 'subscribe' ) ); ?>">Subscribe</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</header>
<!-- /wp:group -->
