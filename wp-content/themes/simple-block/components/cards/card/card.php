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
wp_enqueue_script( 'component-card-script', get_template_directory_uri() . '/components/cards/card/card.js', array(), '1.0', array( 'strategy' => 'defer' ) );

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
			$base64_string = get_post_meta( $image_id, '_lqip_base64', true );

			$attr = array(
				'class' => 'custom-card-featured-image',
				'alt'   => $alt_text
			);

			// if ( $base64_string ) {
			// 	// 1. Get the real image URL and responsive srcset data
			// 	$real_src    = wp_get_attachment_image_url( $image_id, 'full-hero-size' );
			// 	$real_srcset = wp_get_attachment_image_srcset( $image_id, 'full-hero-size' );
			// 	$real_sizes  = wp_get_attachment_image_sizes( $image_id, 'full-hero-size' );

			// 	// 2. Add our blur class and store the real URLs in data attributes
			// 	$attr['class']      .= ' lazy-blur';
			// 	$attr['data-src']    = $real_src;
			// 	$attr['data-srcset'] = $real_srcset ? $real_srcset : '';
			// 	$attr['data-sizes']  = $real_sizes ? $real_sizes : '';

			// 	// 3. Override the default HTML output to show the base64 first
			// 	$attr['src']    = $base64_string; 
			// 	$attr['srcset'] = ''; // Forces WP not to print the real srcset immediately
			// 	$attr['sizes']  = ''; 
			// }

			// // Print the image
			// echo wp_get_attachment_image(
			// 	$image_id,
			// 	'full-hero-size',
			// 	false,
			// 	$attr
			// ); 


			if ( $base64_string ) {
				// 1. Fetch the raw data manually
				$real_src    = wp_get_attachment_image_url( $image_id, 'full-hero-size' );
				$real_srcset = wp_get_attachment_image_srcset( $image_id, 'full-hero-size' );
				$real_sizes  = wp_get_attachment_image_sizes( $image_id, 'full-hero-size' );

				// 2. Build the HTML tag exactly how we want it. No WordPress interference.
				echo sprintf(
					'<img src="%s" data-src="%s" data-srcset="%s" data-sizes="%s" class="custom-card-featured-image lazy-blur" alt="%s" />',
					esc_attr( $base64_string ),
					esc_url( $real_src ),
					esc_attr( $real_srcset ? $real_srcset : '' ),
					esc_attr( $real_sizes ? $real_sizes : '' ),
					esc_attr( $alt_text )
				);
			} else {
				// Fallback just in case the base64 string hasn't been generated yet
				echo wp_get_attachment_image(
					$image_id,
					'full-hero-size',
					false,
					$attr
				);
			}
			
			
			
			?>
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