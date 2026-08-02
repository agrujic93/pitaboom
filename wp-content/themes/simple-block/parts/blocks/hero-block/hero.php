<?php
/**
 * Block Name: Hero
 *
 * This is the template that displays the Hero block.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ci-uikit
 **/

// Create id attribute for specific styling and anchor tag.

if ( isset( $block['anchor'] ) ) {
	$block_id = esc_attr( $block['anchor'] );
} else {
	$block_id = 'ci-hero-' . $block['id'];
}

$main_block_class = 'ci-hero-block ci-block';
$container_class = 'section-full-width';
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

else : /* rendering in editor body */
	?>

	<?php
		$classes = [
			$main_block_class,
			$container_class,
			'ci-has-background',
			'ci-hero-image',
			'ci-hero-modern',
		];

		$wrapper_attributes = get_block_wrapper_attributes([
			'class' => implode(' ', array_map('trim', $classes)),
		]);

		$hero_title = get_field( 'hero_title' );
		$hero_subtitle = get_field( 'hero_subtitle' );
		$hero_background_image = get_field( 'hero_background_image' );
	?>

	<section id="<?php echo esc_attr( $block_id ); ?>" <?php echo $wrapper_attributes; ?>>
		<div class="hero-content-wrp" <?php include(__DIR__ . '/../block-parts/animation-block.php'); ?>>
			<div class="container">
				<div class="hero-wrp hero-modern-content rm-last-child-margin" <?php echo $duration; ?>>
					<div>
						<?php if ( $hero_title ) : ?>
							<div class="reveal-text">
								<h1><?php echo esc_html( $hero_title ); ?></h1>
							</div>
						<?php endif; ?>
						<?php if ( $hero_subtitle ) : ?>
							<div class="hero-subtitle rm-last-child-margin reveal-text"><?php echo wp_kses_post( $hero_subtitle ); ?></div>
						<?php endif; ?>

						<?php if ( have_rows( 'hero_info_items' ) ) : ?>
							<ul class="hero-info-list <?php echo !is_mobile_device() ? 'animation-fade-item' : ''; ?>">
								<?php while ( have_rows( 'hero_info_items' ) ) : the_row(); ?>
									<?php $info_item = get_sub_field( 'info_item' ); ?>
									<?php if ( $info_item ) : ?>
										<li class=""><?php echo esc_html( $info_item ); ?></li>
									<?php endif; ?>
								<?php endwhile; ?>
							</ul>
						<?php endif; ?>

						<?php if ( have_rows( 'hero_buttons' ) ) : ?>
							<div class="hero-buttons">
								<?php while ( have_rows( 'hero_buttons' ) ) : the_row(); ?>
									<?php
									$link = get_sub_field( 'button_link' );
									$button_type = get_sub_field( 'button_type' );
									if ( ! $link ) {
										continue;
									}

									$link_url = $link['url'];
									$link_title = $link['title'];
									$link_target = $link['target'] ? $link['target'] : '_self';
									$button_class = 'btn';
									if ( 'secondary' === $button_type ) {
										$button_class .= ' btn-secondary';
									}
									?>
									<a class="<?php echo esc_attr( $button_class ); ?> <?php echo !is_mobile_device() ? 'animation-fade-item' : ''; ?>" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>">
										<?php echo esc_html( $link_title ); ?>
									</a>
								<?php endwhile; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php if ( $hero_background_image ) : ?>
				<div class="hero-background-image-wrp">
					<?php
					
					$image_alt = get_post_meta( $hero_background_image, '_wp_attachment_image_alt', true );
					$base64_string = get_post_meta( $hero_background_image, '_lqip_base64', true );
					
					if ( $base64_string) {
						// 1. Fetch the raw data manually
						$real_src    = wp_get_attachment_image_url( $hero_background_image, 'full-hero-size' );
						$real_srcset = wp_get_attachment_image_srcset( $hero_background_image, 'full-hero-size' );
						$real_sizes  = wp_get_attachment_image_sizes( $hero_background_image, 'full-hero-size' );

						// 2. Build the HTML tag exactly how we want it. No WordPress interference.
						echo sprintf(
							'<img data-uk-parallax="%s"  src="%s" data-src="%s" data-srcset="%s" data-sizes="%s" class="hero-background-image lazy-blur" alt="%s" />',
							"y: 0,-50%",
							esc_attr( $base64_string ),
							esc_url( $real_src ),
							esc_attr( $real_srcset ? $real_srcset : '' ),
							esc_attr( $real_sizes ? $real_sizes : '' ),
							esc_attr( $image_alt )
						);
					} else {
						// Fallback just in case the base64 string hasn't been generated yet
						echo wp_get_attachment_image(
							$hero_background_image,
							'full-hero-size',
							false,
							array(
								'class' => 'hero-background-image',
								'alt' => $image_alt,
							)
						);
					} ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>
