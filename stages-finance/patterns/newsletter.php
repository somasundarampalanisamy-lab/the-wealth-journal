<?php
/**
 * Title: Newsletter sign-up band
 * Slug: stages-finance/newsletter
 * Categories: stages-finance, call-to-action
 */
?>
<!-- wp:group {"tagName":"section","anchor":"newsletter","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section id="newsletter" class="wp-block-group" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--60)">
	<?php if ( shortcode_exists( 'ls_signup' ) ) : ?>
	<!-- wp:shortcode -->
	[ls_signup stage="starting-out" title="The 5-minute weekly email for your first salary" text="One practical money lesson every week, plus the free First Job Budget Checklist when you join. No spam, no stock tips." button="Send me the checklist"]
	<!-- /wp:shortcode -->
	<?php else : ?>
	<!-- wp:heading {"textAlign":"center"} --><h2 class="wp-block-heading has-text-align-center">The 5-minute weekly email for your first salary</h2><!-- /wp:heading -->
	<!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">Activate the Life Stages plugin to show the sign-up form here.</p><!-- /wp:paragraph -->
	<?php endif; ?>
</section>
<!-- /wp:group -->
