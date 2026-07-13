<?php
/**
 * Block Name: Cards Block
 *
 * This is the template that displays the Cards.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ci-uikit
 **/

// Create id attribute for specific styling and anchor tag.

if ( isset( $block['anchor'] ) ) {
	$block_id = esc_attr( $block['anchor'] );
} else {
	$block_id = 'ci-cards-' . $block['id'];
}

$main_block_class = 'ci-cards-block ci-block ci-has-background';
$container_class  = 'section-full-width';
if ( 'wide' == $block['align'] ) {
	$container_class = 'section-container-wide';
} elseif ( '' == $block['align'] || 'center' == $block['align'] ) {
	$container_class = 'section-container';
} elseif ( 'left' == $block['align'] ) {
	$container_class = 'container-left';
} elseif ( 'right' == $block['align'] ) {
	$container_class = 'container-right';
}
if ( isset( $block['data']['preview_image_help'] ) ) :    /* rendering in inserter preview  */
	echo '<img src="' . esc_url( get_template_directory_uri() ) . esc_attr( $block['data']['preview_image_help'] ) . '" style="width:100%; height:auto;">';

else : /* Rendering in editor body. */
	?>

	<?php include __DIR__ . '/../block-parts/background-and-text-color-block.php'; ?>

	<section id="<?php echo esc_attr( $block_id ); ?>" <?php echo $wrapper_attributes; ?>>
		<div class="container" <?php include __DIR__ . '/../block-parts/animation-block.php'; ?>>
			<div class="uk-grid uk-grid-large uk-position-relative ci-cards-block-wrp" data-uk-grid>
				<div class="title-intro-wrp">
					<?php if (get_field('title')): ?>
						<div class="uk-width-expand">
							<h2 class="section-title"><?php echo get_field( 'title'); ?></h2>
						</div>
					<?php endif; ?>
					<?php if (get_field('intro')): ?>
						<div class="uk-width-expand rm-last-child-margin intro">
							<?php echo get_field( 'intro' ); ?>
						</div>
					<?php endif; ?>
				</div>
				<?php
				$cards_per_row = get_field('number_of_cards_per_row');
				$source_type = get_field('source_type');
				$images_aspect_ratio = get_field('images_aspect_ratio');
				if ( $source_type === 'manual' && have_rows( 'cards' ) ) : ?>
					<div class="uk-width-1-1 uk-margin-remove-top">
						<div class="uk-grid cards-wrp" data-uk-grid>
							<?php while ( have_rows( 'cards' ) ) : the_row();
								$image = get_sub_field('image');
								$label = get_sub_field('label');
								$title = get_sub_field('title');
								$excerpt = get_sub_field('excerpt');
								$cta_type = get_sub_field('cta_type');
								$link = get_sub_field('link');

								?>
								<div class="uk-width-1-<?php echo $cards_per_row; ?>@m ">
									<?php
										get_template_part('components/cards/card/card', null, [
											'title' => $title,
											'content' => $excerpt,
											'image_id' => $image,
											'custom_classes' => '',
											'link' => $link,
											'label' => $label,
											'aspect_ratio' => $images_aspect_ratio,
											'cta_type' => $cta_type
										]);
									?>
								</div>
							<?php endwhile; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>
