<?php
/**
 * Block Name: Big Cards Block
 *
 * This is the template that displays the Big Cards.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ci-uikit
 **/

// Create id attribute for specific styling and anchor tag.

if ( isset( $block['anchor'] ) ) {
	$block_id = esc_attr( $block['anchor'] );
} else {
	$block_id = 'ci-big-cards-' . $block['id'];
}

$main_block_class = 'ci-big-cards-block ci-block ci-has-background';
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
			<div class="uk-grid uk-grid-large uk-position-relative ci-big-cards-block-wrp" data-uk-grid>
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
				$show_numbers = get_field('show_numbers');
				$number = 1;
				if ( have_rows( 'cards' ) ) : ?>
					<div class="uk-width-1-1 uk-margin-remove-top">
						<div class="uk-grid big-cards-wrp" data-uk-grid>
							<?php while ( have_rows( 'cards' ) ) : the_row();

								$title = get_sub_field('title');
								$excerpt = get_sub_field('excerpt');
								$link = get_sub_field('link');

								?>
								<div class="uk-width-1-2@m ">
									<?php
										get_template_part('components/cards/big-card/big-card', null, [
											'title' => $title,
											'content' => $excerpt,
											'custom_classes' => '',
											'link' => $link,
											'show_numbers' => $show_numbers,
											'number' => $number
										]);
										$number++;
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
