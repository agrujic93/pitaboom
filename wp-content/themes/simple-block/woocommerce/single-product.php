<?php
/**
 * Single Product Page Template
 *
 * Theme override for WooCommerce single product pages.
 *
 * @package Simple Block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Prevent duplicate output: WooCommerce Coming Soon renders on template_include
// for visitors, and this template may still be resolved afterward.
if ( class_exists( '\\Automattic\\WooCommerce\\Internal\\ComingSoon\\ComingSoonHelper' ) ) {
	$coming_soon_helper = new \Automattic\WooCommerce\Internal\ComingSoon\ComingSoonHelper();

	if ( ! current_user_can( 'manage_woocommerce' ) && $coming_soon_helper->is_current_page_coming_soon() ) {
		return;
	}
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div class="wp-site-blocks">
		<header class="wp-block-template-part site-header">
			<?php block_header_area(); ?>
		</header>
        
		<main class="wp-block-group site-main is-layout-flow wp-block-group-is-layout-flow ci-single-product-page" id="wp--skip-link--target">
			<div class="entry-content has-global-padding">
                <?php while ( have_posts() ) : ?>
                    <?php the_post(); ?>
                    <?php wc_get_template_part( 'content', 'single-product' ); ?>
                <?php endwhile; ?>
			</div>
		</main>

		<footer class="wp-block-template-part site-footer">
			<?php block_footer_area(); ?>
		</footer>
	</div>
	<?php wp_footer(); ?>
</body>
</html>
