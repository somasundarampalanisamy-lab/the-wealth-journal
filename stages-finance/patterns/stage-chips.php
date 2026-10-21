<?php
/**
 * Title: Browse-by-stage chips
 * Slug: stages-finance/stage-chips
 * Categories: stages-finance
 * Inserter: no
 */
$stages = array(
	'starting-out'    => 'Starting Out',
	'growing'         => 'Growing',
	'building-wealth' => 'Building Wealth',
);
?>
<!-- wp:buttons {"className":"stage-chips"} -->
<div class="wp-block-buttons stage-chips">
<?php
foreach ( $stages as $slug => $label ) :
	$link = taxonomy_exists( 'stage' ) ? get_term_link( $slug, 'stage' ) : '';
	$link = ( $link && ! is_wp_error( $link ) ) ? $link : stages_finance_page_url( $slug );
	?>
	<!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $label ); ?></a></div><!-- /wp:button -->
<?php endforeach; ?>
</div>
<!-- /wp:buttons -->
