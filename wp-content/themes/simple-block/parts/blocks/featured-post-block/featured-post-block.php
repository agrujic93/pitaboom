<?php
/**
 * Block Name: Featured post
 *
 * This is the template that displays the Featured post block.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ci-uikit
 **/

if ( isset( $block['anchor'] ) ) {
	$block_id = esc_attr( $block['anchor'] );
} else {
	$block_id = 'ci-featured-post-block-' . $block['id'];
}

$main_block_class = 'ci-featured-post-block ci-block ci-has-background';
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

if ( isset( $block['data']['preview_image_help'] ) ) :
	echo '<img src="' . esc_url( get_template_directory_uri() ) . esc_attr( $block['data']['preview_image_help'] ) . '" style="width:100%; height:auto;">';

else :
	include __DIR__ . '/../block-parts/background-and-text-color-block.php';

	$featured_image_id      = get_field( 'featured_post_image' );
	$featured_post_content  = get_field( 'featured_post_content' );
	$featured_post_cta_link = get_field( 'featured_post_cta' );
	?>

	<section id="<?php echo esc_attr( $block_id ); ?>" <?php echo $wrapper_attributes; ?>>
		<div class="container" <?php include __DIR__ . '/../block-parts/animation-block.php'; ?>>
			<div class="featured-post-shell animation-fade-item" <?php echo $duration; ?>>
				<div class="featured-post-grid uk-grid-collapse uk-child-width-1-2@m" data-uk-grid>
					<div>
						<div class="featured-post-image-wrap">
							<?php if ( $featured_image_id ) : ?>
								<?php
								
                                $alt_text = get_post_meta( $featured_image_id, '_wp_attachment_image_alt', true );
                                $base64_string = get_post_meta( $featured_image_id, '_lqip_base64', true );

                                $attr = array(
                                    'class' => 'featured-post-image',
                                    'alt'   => $alt_text,
                                );

                                if ( $base64_string ) {
                                    // 1. Get the real image URL and responsive srcset data
                                    $real_src    = wp_get_attachment_image_url( $featured_image_id, 'full-hero-size' );
                                    $real_srcset = wp_get_attachment_image_srcset( $featured_image_id, 'full-hero-size' );
                                    $real_sizes  = wp_get_attachment_image_sizes( $featured_image_id, 'full-hero-size' );

                                    // 2. Add our blur class and store the real URLs in data attributes
                                    $attr['class']      .= ' lazy-blur';
                                    $attr['data-src']    = $real_src;
                                    $attr['data-srcset'] = $real_srcset ? $real_srcset : '';
                                    $attr['data-sizes']  = $real_sizes ? $real_sizes : '';

                                    // 3. Override the default HTML output to show the base64 first
                                    $attr['src']    = $base64_string; 
                                    $attr['srcset'] = ''; // Forces WP not to print the real srcset immediately
                                    $attr['sizes']  = ''; 
                                }

                                // Print the image
                                echo wp_get_attachment_image(
                                    $featured_image_id,
                                    'full-hero-size',
                                    false,
                                    $attr
                                );
                                
                                
                              
							?>
							<?php else : ?>
								<div class="featured-post-image featured-post-image-placeholder"></div>
							<?php endif; ?>
						</div>
					</div>

					<div>
						<div class="featured-post-content rm-last-child-margin">
							<?php if ( $featured_post_content ) : ?>
								<?php echo $featured_post_content; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>
