<?php

$title = $args['title'] ?? '';
$cta_type = $args['cta_type'] ?? '';
$content  = $args['content'] ?? '';
$image_id = $args['image_id'] ?? null;
$custom_classes = $args['custom_classes'] ?? '';
$link = $args['link'] ?? '';
$label = $args['label'] ?? '';
$aspect_ratio = isset($args['aspect_ratio']) && $args['aspect_ratio'] ? $args['aspect_ratio'] : '1 / 1';

wp_enqueue_style( 'component-card-style', get_template_directory_uri() . '/components/cards/card/card.css' );
wp_enqueue_script( 'component-card-script', get_template_directory_uri() . '/components/cards/card/card.js', array(), '1.0', true );

?>

<div class="custom-card-component animation-fade-item <?php echo $custom_classes; ?>">
	<svg class="card-border-draw" preserveAspectRatio="none">
		<defs>
			<linearGradient id="cardBorderGradient" x1="0%" y1="0%" x2="0%" y2="100%">
				<stop offset="0%" stop-color="#A4551B" />
				<stop offset="100%" stop-color="#C5A279" />
			</linearGradient>
		</defs>
		<path class="card-border-path card-border-path--right" />
		<path class="card-border-path card-border-path--left" />
	</svg>
	<?php if ( $image_id ){ ?>
		<div class="uk-position-relative image-wrapper" style="aspect-ratio: <?php echo $aspect_ratio; ?>;">
			<?php

			$alt_text = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

			echo wp_get_attachment_image(
				$image_id,
				'full-hero-size',
				false,
				array(
					'class' => 'custom-card-featured-image',
					'alt'   => $alt_text
				)
			); ?>
		</div>
	<?php } ?>

	<div class="custom-card-content">
		<?php if($label){ ?>
			<p class="card-label"><?php echo esc_html( $label ); ?></p>
		<?php }
		if($title){ ?>
			<h3 class="h4 card-title"><?php echo esc_html( $title ); ?></h3>
		<?php }
		if ( $content ){ ?>
			<p class="card-content"><?php echo esc_html( $content ); ?></p>
		<?php } ?>
	</div>
	<?php if( $link ):
			$link_url = $link['url'];
			$link_title = $link['title'];
			$link_target = $link['target'] ? $link['target'] : '_self';
			?>
				<a class="card-link <?php echo isset($cta_type) && $cta_type === 'button' ? 'card-link-btn' : ''; ?>" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo isset($cta_type) && $cta_type === 'button' ? esc_html( $link_title ) : ''; ?></a>
		<?php endif; ?>
</div>