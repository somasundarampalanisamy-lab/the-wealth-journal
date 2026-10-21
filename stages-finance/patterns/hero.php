<?php
/**
 * Title: Home hero with stage picker
 * Slug: stages-finance/hero
 * Categories: stages-finance, banner
 * Description: Headline plus three clickable stage cards and a trust strip.
 */
?>
<!-- wp:group {"tagName":"section","className":"home-hero","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group home-hero" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:paragraph {"align":"center","className":"eyebrow","style":{"color":{"text":"#4A5568"}}} -->
	<p class="has-text-align-center eyebrow has-text-color" style="color:#4A5568">Personal finance &amp; value investing, explained plainly</p>
	<!-- /wp:paragraph -->
	<!-- wp:heading {"textAlign":"center","level":1,"align":"wide","fontSize":"huge"} -->
	<h1 class="wp-block-heading alignwide has-text-align-center has-huge-font-size">Money advice that grows up with you.</h1>
	<!-- /wp:heading -->
	<!-- wp:paragraph {"align":"center","className":"lead"} -->
	<p class="has-text-align-center lead">From your first payslip to your first crore, without jargon or hype. Where are you right now?</p>
	<!-- /wp:paragraph -->
	<!-- wp:spacer {"height":"16px"} --><div style="height:16px" aria-hidden="true" class="wp-block-spacer"></div><!-- /wp:spacer -->
	<?php echo stages_finance_stage_cards_markup( 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<!-- wp:paragraph {"align":"center","className":"is-muted","style":{"typography":{"fontSize":"0.9375rem"}}} -->
	<p class="has-text-align-center is-muted" style="font-size:0.9375rem">Not sure which one fits? <a href="<?php echo esc_url( stages_finance_page_url( 'start-here' ) ); ?>"><strong>Take the 30-second check</strong></a></p>
	<!-- /wp:paragraph -->
</section>
<!-- /wp:group -->
<!-- wp:group {"tagName":"section","className":"trust-strip","style":{"spacing":{"padding":{"top":"22px","bottom":"22px"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group trust-strip" style="padding-top:22px;padding-bottom:22px">
	<!-- wp:list {"className":"trust-items"} -->
	<ul class="wp-block-list trust-items"><!-- wp:list-item --><li>23 years in IT</li><!-- /wp:list-item --><!-- wp:list-item --><li>15 years of value investing</li><!-- /wp:list-item --><!-- wp:list-item --><li>No tips, no hype, just principles</li><!-- /wp:list-item --></ul>
	<!-- /wp:list -->
</section>
<!-- /wp:group -->
