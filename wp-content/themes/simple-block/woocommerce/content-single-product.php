<?php
/**
 * Content Single Product
 *
 * @package Simple Block
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product instanceof WC_Product ) {
	return;
}

$product_id         = $product->get_id();
$product_image_id   = $product->get_image_id();
$product_title      = get_the_title( $product_id );
$product_excerpt    = $product->get_short_description();
$product_attributes = $product->get_attributes();
$product_tags       = get_the_terms( $product_id, 'product_tag' );

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}

if ( empty( $product_excerpt ) ) {
	$product_excerpt = get_the_excerpt( $product_id );
}

// Default duration fallback when block animation fields are not configured.
$duration = 'style="animation-duration:600ms;"';

$visible_attributes = array();

foreach ( $product_attributes as $attribute ) {
	if ( ! $attribute->get_visible() ) {
		continue;
	}

	$attribute_label = wc_attribute_label( $attribute->get_name() );
	$attribute_value = '';

	if ( $attribute->is_taxonomy() ) {
		$terms = wc_get_product_terms( $product_id, $attribute->get_name(), array( 'fields' => 'names' ) );
		if ( ! empty( $terms ) ) {
			$attribute_value = implode( ', ', $terms );
		}
	} else {
		$options = $attribute->get_options();
		if ( ! empty( $options ) ) {
			$attribute_value = implode( ', ', $options );
		}
	}

	if ( '' !== trim( $attribute_value ) ) {
		$visible_attributes[] = array(
			'label' => $attribute_label,
			'value' => $attribute_value,
		);
	}
}
?>
<?php wc_print_notices(); ?>
<section class="section-container ci-block ci-product-hero ci-has-background" id="product-<?php the_ID(); ?>" <?php wc_product_class( 'ci-product-single', $product ); ?>>
    <div class="hero-content-wrp">
        <div class="container" data-uk-scrollspy="cls: uk-animation-slide-bottom-small; target: .animation-fade-item; repeat: false;">
            <div class="hero-wrp ci-product-hero-header rm-last-child-margin">
                <div>
                    <?php // TODO: Bring back $visible_attributes output here when needed. ?>
                    <?php if ( ! empty( $product_tags ) && ! is_wp_error( $product_tags ) ) : ?>
                        <ul class="ci-product-hero-attributes" aria-label="<?php esc_attr_e( 'Product tags', 'simple-block' ); ?>">
                            <?php foreach ( $product_tags as $tag ) : ?>
                                <li class="reveal-text">
                                    <!-- <span class="ci-product-hero-attr-label"><?php esc_html_e( 'Tag', 'simple-block' ); ?></span> -->
                                    <span class="ci-product-hero-attr-value"><?php echo esc_html( $tag->name ); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if ( $product_title ) : ?>
                        <h1 class="reveal-text"><?php echo esc_html( $product_title ); ?></h1>
                    <?php endif; ?>

                    <?php if ( $product_excerpt ) : ?>
                        <div class="ci-product-hero-description rm-last-child-margin reveal-text">
                            <?php echo wp_kses_post( wpautop( $product_excerpt ) ); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="uk-flex uk-margin-medium-left@m">
                    <a class="btn animation-fade-item" <?php echo ( ! is_mobile_device() && isset( $duration ) ) ? $duration : ''; ?> href="#">Kontaktirajte nas</a>
                </div>
            </div>
        </div>

        <?php if ( $product_image_id ) : ?>
            <div class="ci-product-hero-image-wrp">
                <?php
                $base64_string = get_post_meta( $product_image_id, '_lqip_base64', true );
                $alt_text = get_post_meta( $product_image_id, '_wp_attachment_image_alt', true );

                if ( $base64_string ) {
                    // 1. Fetch the raw data manually
                    $real_src    = wp_get_attachment_image_url( $product_image_id, 'full-hero-size' );
                    $real_srcset = wp_get_attachment_image_srcset( $product_image_id, 'full-hero-size' );
                    $real_sizes  = wp_get_attachment_image_sizes( $product_image_id, 'full-hero-size' );

                    // 2. Build the HTML tag exactly how we want it. No WordPress interference.
                    echo sprintf(
                        '<img src="%s" data-src="%s" data-srcset="%s" data-sizes="%s" class="ci-product-hero-image lazy-blur" alt="%s" />',
                        esc_attr( $base64_string ),
                        esc_url( $real_src ),
                        esc_attr( $real_srcset ? $real_srcset : '' ),
                        esc_attr( $real_sizes ? $real_sizes : '' ),
                        esc_attr( $alt_text )
                    );
                } else {
                    // Fallback just in case the base64 string hasn't been generated yet
                    echo wp_get_attachment_image(
                        $product_image_id,
                        'full',
                        false,
                        array(
                            'class'    => 'ci-product-hero-image',
                            'loading'  => 'eager',
                            'decoding' => 'async',
                        )
                    );
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>



<?php do_action( 'woocommerce_after_single_product' ); ?>
