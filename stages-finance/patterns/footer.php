<?php
/**
 * Title: Site footer
 * Slug: stages-finance/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: no
 */
?>
<!-- wp:group {"tagName":"footer","className":"site-footer","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<footer class="wp-block-group site-footer" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40)">
	<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"left":"40px","top":"32px"}}}} -->
	<div class="wp-block-columns alignwide">
		<!-- wp:column {"width":"40%"} -->
		<div class="wp-block-column" style="flex-basis:40%">
			<!-- wp:paragraph {"className":"footer-brand"} -->
			<p class="footer-brand"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
			<!-- /wp:paragraph -->
			<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.9375rem"}}} -->
			<p style="font-size:0.9375rem">Money advice that grows up with you. Plain-language lessons on salary, tax and value investing, from your first payslip to your first crore.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Your stage</h2><!-- /wp:heading -->
			<!-- wp:list -->
			<ul class="wp-block-list"><!-- wp:list-item --><li><a href="<?php echo esc_url( stages_finance_page_url( 'starting-out' ) ); ?>">Starting Out</a></li><!-- /wp:list-item --><!-- wp:list-item --><li><a href="<?php echo esc_url( stages_finance_page_url( 'growing' ) ); ?>">Growing</a></li><!-- /wp:list-item --><?php if ( 'off' !== stages_finance_stage_state( 'building-wealth' ) ) : ?><!-- wp:list-item --><li><a href="<?php echo esc_url( stages_finance_page_url( 'building-wealth' ) ); ?>">Building Wealth</a><?php if ( 'soon' === stages_finance_stage_state( 'building-wealth' ) ) : ?> <span class="soon-badge">Soon</span><?php endif; ?></li><!-- /wp:list-item --><?php endif; ?><!-- wp:list-item --><li><a href="<?php echo esc_url( stages_finance_page_url( 'start-here' ) ); ?>">Not sure? Start here</a></li><!-- /wp:list-item --></ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">Learn</h2><!-- /wp:heading -->
			<!-- wp:list -->
			<ul class="wp-block-list"><!-- wp:list-item --><li><a href="<?php echo esc_url( stages_finance_page_url( 'value-investing' ) ); ?>">Value Investing</a></li><!-- /wp:list-item --><!-- wp:list-item --><li><a href="<?php echo esc_url( stages_finance_category_url( 'taxes' ) ); ?>">Taxes</a></li><!-- /wp:list-item --><!-- wp:list-item --><li><a href="<?php echo esc_url( stages_finance_page_url( 'tools' ) ); ?>">Calculators</a></li><!-- /wp:list-item --><!-- wp:list-item --><li><a href="<?php echo esc_url( stages_finance_page_url( 'articles' ) ); ?>">All articles</a></li><!-- /wp:list-item --></ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:heading {"level":2} --><h2 class="wp-block-heading">About</h2><!-- /wp:heading -->
			<!-- wp:list -->
			<ul class="wp-block-list"><!-- wp:list-item --><li><a href="<?php echo esc_url( stages_finance_page_url( 'about' ) ); ?>">Our philosophy</a></li><!-- /wp:list-item --><!-- wp:list-item --><li><a href="<?php echo esc_url( stages_finance_page_url( 'subscribe' ) ); ?>">Newsletter</a></li><!-- /wp:list-item --></ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
	<!-- wp:paragraph {"align":"wide","className":"footer-small","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
	<p class="alignwide footer-small" style="margin-top:var(--wp--preset--spacing--50)">© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. Everything on this site is for education only and is not investment, tax or legal advice. We are not SEBI-registered investment advisers and do not recommend specific stocks or funds. Please consult a qualified professional before making financial decisions.</p>
	<!-- /wp:paragraph -->
</footer>
<!-- /wp:group -->
