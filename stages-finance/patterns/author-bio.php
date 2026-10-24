<?php
/**
 * Title: Author bio box
 * Slug: stages-finance/author-bio
 * Categories: stages-finance
 * Inserter: yes
 */
?>
<!-- wp:group {"className":"author-bio","style":{"spacing":{"blockGap":"16px"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group author-bio">
	<!-- wp:paragraph {"className":"author-avatar"} --><p class="author-avatar">YN</p><!-- /wp:paragraph -->
	<!-- wp:group {"style":{"spacing":{"blockGap":"6px"}},"layout":{"type":"flex","orientation":"vertical"}} -->
	<div class="wp-block-group">
		<!-- wp:paragraph --><p><strong>Written by [Your Name]</strong></p><!-- /wp:paragraph -->
		<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.9375rem"}},"textColor":"ink-soft"} -->
		<p class="has-ink-soft-color has-text-color" style="font-size:0.9375rem">24 years in corporate IT, 15 years of hands-on value investing in direct stocks and mutual funds, and a disciplined 60/40 portfolio. I write the guide I wish I’d had at 21. <a href="<?php echo esc_url( stages_finance_page_url( 'about' ) ); ?>">More about me</a></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
