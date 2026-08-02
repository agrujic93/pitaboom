<?php
/**
 * Simple Block functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Simple Block
 * @since Simple Block 1.0
 */

/**
 * Include Block Generator
 */
add_action( 'after_setup_theme', function() {
	require get_template_directory() . '/inc/block-generator.php';
} );

/**
 * Register block styles.
 */

if ( ! function_exists( 'simple_block_block_styles' ) ) :
	/**
	 * Register custom block styles
	 *
	 * @since Simple Block 1.0
	 * @return void
	 */
	function simple_block_block_styles() {

		register_block_style(
			'core/details',
			array(
				'name'         => 'arrow-icon-details',
				'label'        => __( 'Arrow icon', 'simple-block' ),
				/*
				 * Styles for the custom Arrow icon style of the Details block
				 */
				'inline_style' => '
				.is-style-arrow-icon-details {
					padding-top: var(--wp--preset--spacing--10);
					padding-bottom: var(--wp--preset--spacing--10);
				}

				.is-style-arrow-icon-details summary {
					list-style-type: "\2193\00a0\00a0\00a0";
				}

				.is-style-arrow-icon-details[open]>summary {
					list-style-type: "\2192\00a0\00a0\00a0";
				}',
			)
		);
		register_block_style(
			'core/post-terms',
			array(
				'name'         => 'pill',
				'label'        => __( 'Pill', 'simple-block' ),
				/*
				 * Styles variation for post terms
				 * https://github.com/WordPress/gutenberg/issues/24956
				 */
				'inline_style' => '
				.is-style-pill a,
				.is-style-pill span:not([class], [data-rich-text-placeholder]) {
					display: inline-block;
					background-color: var(--wp--preset--color--base-2);
					padding: 0.375rem 0.875rem;
					border-radius: var(--wp--preset--spacing--20);
				}

				.is-style-pill a:hover {
					background-color: var(--wp--preset--color--contrast-3);
				}',
			)
		);
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'simple-block' ),
				/*
				 * Styles for the custom checkmark list block style
				 * https://github.com/WordPress/gutenberg/issues/51480
				 */
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;

add_action( 'init', 'simple_block_block_styles' );

/**
 * Register pattern categories.
 */

if ( ! function_exists( 'simple_block_pattern_categories' ) ) :
	/**
	 * Register pattern categories
	 *
	 * @since Simple Block 1.0
	 * @return void
	 */
	function simple_block_pattern_categories() {

		register_block_pattern_category(
			'simple_block_page',
			array(
				'label'       => _x( 'Pages', 'Block pattern category', 'simple-block' ),
				'description' => __( 'A collection of full page layouts.', 'simple-block' ),
			)
		);
	}
endif;

add_action( 'init', 'simple_block_pattern_categories' );

// Adds theme support for post formats.
if ( ! function_exists( 'simple_block_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 */
	function simple_block_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'simple_block_post_format_setup' );

// Registers block binding sources.
if ( ! function_exists( 'simple_block_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 */
	function simple_block_register_block_bindings() {
		register_block_bindings_source(
			'simple-block/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'simple-block' ),
				'get_value_callback' => 'simple_block_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'simple_block_register_block_bindings' );

// Registers block binding callback function for the post format name.
if ( ! function_exists( 'simple_block_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function simple_block_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;

// Custom Body Class
function custom_body_class( $classes ) {

	$classes[] = 'animation-fade-container';

	return $classes;
}
add_filter( 'body_class', 'custom_body_class' );

// Include Scripts
function ag_uikit_scripts() {

	$uikit_js_path = get_stylesheet_directory() . '/assets/js/uikit.min.js';

	wp_enqueue_script(
		'uikit-js',
		get_stylesheet_directory_uri() . '/assets/js/uikit.min.js',
		array(),
		file_exists( $uikit_js_path ) ? filemtime( $uikit_js_path ) : '3.21.11',
		array( 'strategy' => 'defer' ) // 52 KiB - layout from CSS, JS only needed for interactivity
	);

	$uikit_icons_js_path = get_stylesheet_directory() . '/assets/js/uikit-icons.min.js';

	wp_enqueue_script(
		'uikit-icons-js',
		get_stylesheet_directory_uri() . '/assets/js/uikit-icons.min.js',
		array(),
		file_exists( $uikit_icons_js_path ) ? filemtime( $uikit_icons_js_path ) : '3.21.11',
		array( 'strategy' => 'defer' )
	);

	$main_js_path = get_stylesheet_directory() . '/assets/js/main.js';

	wp_enqueue_script(
		'main-js',
		get_stylesheet_directory_uri() . '/assets/js/main.js',
		array( 'jquery' ),
		file_exists( $main_js_path ) ? filemtime( $main_js_path ) : '1.0.0',
		array( 'strategy' => 'defer' )
	);
}
add_action( 'enqueue_block_assets', 'ag_uikit_scripts' );

// Include UIkit CSS
function ag_uikit_frontend() {
	// Path to your uikit-style.css file
	$css_file_path = get_stylesheet_directory() . '/assets/css/uikit-style.css';

	// Enqueue the UIkit style with file modification time as the version number
	wp_enqueue_style(
		'uikit-style', // Handle for the stylesheet
		get_stylesheet_directory_uri() . '/assets/css/uikit-style.css', // URL to the CSS file
		array(), // Dependencies, if any
		file_exists( $css_file_path ) ? filemtime( $css_file_path ) : '1.0.0' // Version based on file modification time
	);
}
add_action( 'init', 'ag_uikit_frontend', 1 );


function is_mobile_device() {
	$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
	return preg_match('/Android|webOS|iPhone|iPad|iPod/i', $user_agent);
}


// Include GSAP animations library and styles
function ag_enqueue_gsap_animations() {
		
	// Enqueue GSAP library from local theme assets.
	$gsap_js_path = get_stylesheet_directory() . '/assets/js/vendor/gsap.min.js';
	if ( file_exists( $gsap_js_path ) ) {
		wp_enqueue_script(
			'gsap-lib',
			get_stylesheet_directory_uri() . '/assets/js/vendor/gsap.min.js',
			array(),
			filemtime( $gsap_js_path )
		);
	}

	// Skip GSAP entirely on mobile devices - improves performance
	

	// Enqueue ScrollTrigger plugin from local theme assets.
	$scroll_trigger_js_path = get_stylesheet_directory() . '/assets/js/vendor/ScrollTrigger.min.js';
	if ( file_exists( $scroll_trigger_js_path ) ) {
		wp_enqueue_script(
			'gsap-scroll-trigger',
			get_stylesheet_directory_uri() . '/assets/js/vendor/ScrollTrigger.min.js',
			array( 'gsap-lib' ),
			filemtime( $scroll_trigger_js_path )
		);
	}

	// Enqueue Flip plugin from local theme assets.
	$flip_js_path = get_stylesheet_directory() . '/assets/js/vendor/Flip.min.js';
	if ( file_exists( $flip_js_path ) ) {
		wp_enqueue_script(
			'gsap-flip',
			get_stylesheet_directory_uri() . '/assets/js/vendor/Flip.min.js',
			array( 'gsap-lib' ),
			filemtime( $flip_js_path )
		);
	}

	// Enqueue ScrollSmoother plugin from local theme assets.
	$scroll_smoother_js_path = get_stylesheet_directory() . '/assets/js/vendor/ScrollSmoother.min.js';
	if ( file_exists( $scroll_smoother_js_path ) ) {
		wp_enqueue_script(
			'gsap-scroll-smoother',
			get_stylesheet_directory_uri() . '/assets/js/vendor/ScrollSmoother.min.js',
			array( 'gsap-lib', 'gsap-scroll-trigger' ),
			filemtime( $scroll_smoother_js_path )
		);
	}

	// Enqueue SplitType plugin from local theme assets.
	$split_type_js_path = get_stylesheet_directory() . '/assets/js/vendor/split-type.min.js';
	if ( file_exists( $split_type_js_path ) ) {
		wp_enqueue_script(
			'split-type',
			get_stylesheet_directory_uri() . '/assets/js/vendor/split-type.min.js',
			array(),
			filemtime( $split_type_js_path )
		);
	}

	// // Enqueue custom animations script
	$animations_js_path = get_stylesheet_directory() . '/assets/js/animations.js';
	wp_enqueue_script(
		'animations-js',
		get_stylesheet_directory_uri() . '/assets/js/animations.js',
		array( 'gsap-lib', 'gsap-scroll-trigger', 'gsap-scroll-smoother', 'split-type' ),
		filemtime( $animations_js_path )
	);

	// // Enqueue animations CSS
	$animations_css_path = get_stylesheet_directory() . '/assets/css/animations.css';
	wp_enqueue_style(
		'animations-css',
		get_stylesheet_directory_uri() . '/assets/css/animations.css',
		array(),
		filemtime( $animations_css_path )
	);
}
add_action( 'enqueue_block_assets', 'ag_enqueue_gsap_animations' );

// Include Custom CSS on frontend
function mytheme_enqueue_block_assets() {
	// Path to your frontend-custom-style.css file
	$css_file_path = get_template_directory() . '/assets/css/frontend-custom-style.css';

	// Enqueue the frontend custom style with file modification time as the version number
	wp_enqueue_style(
		'frontend-custom-style', // Handle for the stylesheet
		get_template_directory_uri() . '/assets/css/frontend-custom-style.css', // URL to the CSS file
		array(), // Dependencies, if any
		file_exists( $css_file_path ) ? filemtime( $css_file_path ) : '1.0.0' // Version based on file modification time
	);
}
add_action( 'enqueue_block_assets', 'mytheme_enqueue_block_assets' );

// Include Custom CSS on editor
function mytheme_enqueue_block_editor_assets() {
	// Absolute path to the CSS file for filemtime
	$css_file_path = get_template_directory() . '/assets/css/backend-custom-style.css';

	// URL to the CSS file for wp_enqueue_style
	$css_file_url = get_template_directory_uri() . '/assets/css/backend-custom-style.css';

	// Enqueue the backend custom style with file modification time as the version number
	wp_enqueue_style(
		'backend-custom-style', // Handle for the stylesheet
		$css_file_url, // URL to the CSS file
		array(), // Dependencies, if any
		file_exists( $css_file_path ) ? filemtime( $css_file_path ) : '1.0.0' // Version based on file modification time
	);
}
add_action( 'enqueue_block_editor_assets', 'mytheme_enqueue_block_editor_assets' );

/**
 * Register block styles.
 */
function ci_register_blocks_styles() {
	// Absolute path to the CSS file for filemtime
	$css_file_path = get_template_directory() . '/assets/swiper/swiper-bundle.min.css';

	// URL to the CSS file for wp_enqueue_style
	$css_file_url = get_template_directory_uri() . '/assets/swiper/swiper-bundle.min.css';

	wp_register_style(
		'swiper-style', // Handle for the stylesheet
		$css_file_url, // URL to the CSS file
		array(), // Dependencies, if any
		file_exists( $css_file_path ) ? filemtime( $css_file_path ) : '1.0.0', // Version based on file modification time
	);
}
add_action( 'init', 'ci_register_blocks_styles' );

/**
 * Register block scripts.
 */
function cwp_register_block_script() {
	// Absolute path to the swiper file for filemtime
	$swiper_file_path = get_template_directory() . '/assets/swiper/swiper-bundle.min.js';

	// URL to the swiper file
	$swiper_file_url = get_template_directory_uri() . '/assets/swiper/swiper-bundle.min.js';

	wp_register_script(
		'swiper', // Handle for the stylesheet
		$swiper_file_url, // URL to the CSS file
		array('jquery'), // Dependencies, if any
		file_exists( $swiper_file_path ) ? filemtime( $swiper_file_path ) : '1.0.0', // Version based on file modification time
		array( 'strategy' => 'defer' )
	);

	// Absolute path to the swiper file for filemtime
	$hero_slider_path = get_template_directory() . '/parts/blocks/hero-block/hero-slider.js';

	// URL to the swiper file
	$hero_slider_url = get_template_directory_uri() . '/parts/blocks/hero-block/hero-slider.js';

	wp_register_script(
		'hero-slider-js',
		$hero_slider_url, // URL to the file
		array('swiper', 'acf'), // Dependencies, if any
		file_exists( $hero_slider_path ) ? filemtime( $hero_slider_path ) : '1.0.0', // Version based on file modification time
		array( 'strategy' => 'defer' )
	);

	// Absolute path to the swiper file for filemtime
	$testimonials_slider_path = get_template_directory() . '/parts/blocks/testimonials-block/testimonials-slider.js';

	// URL to the swiper file
	$testimonials_slider_url = get_template_directory_uri() . '/parts/blocks/testimonials-block/testimonials-slider.js';

	wp_register_script(
		'testimonials-slider-js',
		$testimonials_slider_url, // URL to the file
		array('swiper', 'acf'), // Dependencies, if any
		file_exists( $testimonials_slider_path ) ? filemtime( $testimonials_slider_path ) : '1.0.0', // Version based on file modification time
		array( 'strategy' => 'defer' )
	);

	// Absolute path to the swiper file for filemtime
	$partners_slider_path = get_template_directory() . '/parts/blocks/partners-block/partners-slider.js';

	// URL to the swiper file
	$partners_slider_url = get_template_directory_uri() . '/parts/blocks/partners-block/partners-slider.js';

	wp_register_script(
		'partners-slider-js',
		$partners_slider_url, // URL to the file
		array('swiper', 'acf'), // Dependencies, if any
		file_exists( $partners_slider_path ) ? filemtime( $partners_slider_path ) : '1.0.0', // Version based on file modification time
		array( 'strategy' => 'defer' )
	);


}
add_action( 'init', 'cwp_register_block_script' );

/**
 * Create Blocks.
 */
function create_custom_blocks() {
	// get an array of all of the block.json files in my blocks directory
	$block_json_files = glob( get_template_directory() . '/parts/blocks/**/block.json' );
	// auto register all blocks that were found.
	foreach ( $block_json_files as $block_json_file ) {
		register_block_type( $block_json_file );
	}
}

add_action( 'init', 'create_custom_blocks' );

/**
 * Filter block categories when post is provided.
 *
 * @param array        $block_categories The list of block categories.
 * @param WP_Post|null $editor_context   The post object or null if not available.
 * @return array The modified list of block categories.
 */
function filter_block_categories_when_post_provided( $block_categories, $editor_context ) {
	$custom_category_one = array();
	if ( ! empty( $editor_context->post ) ) {
		$custom_category_one = array(
			'slug'  => 'custom-blocks',
			'title' => __( 'Custom Blocks', 'custom-plugin' ),
			'icon'  => null,
		);
		array_unshift( $block_categories, $custom_category_one );
	}
	return $block_categories;
}

add_filter( 'block_categories_all', 'filter_block_categories_when_post_provided', 10, 2 );

/**
 * ACF Local JSON to /uploads/acf-json with slugged filenames
 * - Saves & loads from uploads
 * - Renames files to a slug from the group title (dashes)
 * - Avoids slug collisions by appending -{group_key} when needed
 * - Cleans up old group_*.json and duplicate slug files
 */

/**
 * Ensure uploads/acf-json exists and return its path.
 */
function my_acf_json_dir_path(): string {
	$upload_dir   = wp_upload_dir();
	$acf_json_dir = trailingslashit( $upload_dir['basedir'] ) . 'acf-json';

	// Create the directory if it doesn't exist.
	if ( ! is_dir( $acf_json_dir ) ) {
		wp_mkdir_p( $acf_json_dir );
	}

	return $acf_json_dir;
}

/**
 * Save all ACF JSON to uploads/acf-json.
 */
function my_acf_json_save_path( $path ) {
	$dir = my_acf_json_dir_path();

	// As a safety net, only override if the directory is writable.
	if ( is_dir( $dir ) && is_writable( $dir ) ) {
		return $dir;
	}

	// Fall back to original path if something's off.
	return $path;
}
add_filter( 'acf/settings/save_json', 'my_acf_json_save_path' );

/**
 * Load ACF JSON from uploads/acf-json (smart: append if exists).
 */
function my_acf_json_load_path( $paths ) {
	$dir = my_acf_json_dir_path();

	// Optionally remove the default theme path (uncomment if you want uploads-only)
	// unset( $paths[0] );

	if ( is_dir( $dir ) ) {
		$paths[] = $dir;
	}

	return $paths;
}
add_filter( 'acf/settings/load_json', 'my_acf_json_load_path' );

/**
 * Build a slugged filename for the JSON.
 * NOTE: Correct filter is acf/json/save_file_name (ACF ≥ 6.2).
 */
function my_acf_json_filename( $filename, $post, $load_path ) {
	// Defensive checks.
	if ( empty( $post['title'] ) ) {
		return $filename;
	}

	// Slug from the group title (uses WP's sanitize_title for accents & safety).
	$slug = sanitize_title( $post['title'] );

	$dir       = my_acf_json_dir_path();
	$group_key = isset( $post['key'] ) ? $post['key'] : '';

	// Preferred filename.
	$preferred = $slug . '.json';
	$preferred_path = trailingslashit( $dir ) . $preferred;

	// If a file with the slug already exists but belongs to a *different* group,
	// use a disambiguated filename that includes the group key.
	if ( file_exists( $preferred_path ) && $group_key ) {
		// If the existing slug.json belongs to *this* group, keep it.
		$belongs_to_slug = false;
		$json = @file_get_contents( $preferred_path );
		if ( $json ) {
			$data = json_decode( $json, true );
			if ( isset( $data['key'] ) && $data['key'] === $group_key ) {
				$belongs_to_slug = true;
			}
		}

		if ( ! $belongs_to_slug ) {
			return $slug . '-' . $group_key . '.json';
		}
	}

	// Default to plain slug.json
	return $preferred;
}
add_filter( 'acf/json/save_file_name', 'my_acf_json_filename', 10, 3 );

/**
 * Cleanup after saving a field group:
 * - Remove legacy group_{key}.json
 * - Remove any duplicate slug* files that do not match this group's key
 */
function my_acf_json_cleanup( $post ) {
	if ( empty( $post['key'] ) || empty( $post['title'] ) ) {
		return;
	}

	$dir       = my_acf_json_dir_path();
	$group_key = $post['key'];
	$slug      = sanitize_title( $post['title'] );

	// 1) Remove legacy default-named file: group_{key}.json
	$legacy = trailingslashit( $dir ) . $group_key . '.json';
	if ( file_exists( $legacy ) ) {
		@unlink( $legacy );
	}

	// 2) Remove any duplicate slug files that belong to another group.
	$pattern = trailingslashit( $dir ) . $slug . '*.json';
	foreach ( glob( $pattern ) as $file ) {
		// Keep the current group's own file(s)
		$json = @file_get_contents( $file );
		if ( ! $json ) {
			continue;
		}
		$data = json_decode( $json, true );
		if ( empty( $data['key'] ) ) {
			// If file is malformed or has no key, treat as duplicate & remove.
			@unlink( $file );
			continue;
		}
		if ( $data['key'] !== $group_key ) {
			@unlink( $file );
		}
	}
}
add_action( 'acf/update_field_group', 'my_acf_json_cleanup', 20 );

/**
 * Add custom image sizes
 */
if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'blog-thumb', 410, 410, true );
	add_image_size( 'half-content', 860, 860 );
	add_image_size( 'full-hero-size', 1920 );
}

/**
 * Modify the TinyMCE editor options to include default block palette colors.
 *
 * @param array $init An array of TinyMCE editor settings.
 * @return array The modified array of TinyMCE editor settings.
 */
function my_mce4_options( $init ) {

	// Get the default block palette colors from theme.json or WordPress defaults.
	$default_palette = wp_get_global_settings( array( 'color', 'palette', 'theme' ) );

	// Initialize an empty string for TinyMCE colors.
	$default_colours = '';

	// Loop through the palette and create the color list.
	if ( ! empty( $default_palette ) && is_array( $default_palette ) ) {
		foreach ( $default_palette as $color ) {
			// Remove the leading '#' from the color if it exists.
			$hex_color        = ltrim( $color['color'], '#' );
			$default_colours .= '"' . esc_attr( $hex_color ) . '", "' . esc_attr( $color['name'] ) . '",';
		}

		// Remove the trailing comma.
		$default_colours = rtrim( $default_colours, ',' );
	}

	// Build colour grid with the default block palette colors.
	$init['textcolor_map'] = '[' . $default_colours . ']';

	// Change the number of rows in the grid if the number of colors changes.
	// 8 swatches per row.
	$init['textcolor_rows'] = ceil( count( $default_palette ) / 8 );

	return $init;
}
add_filter( 'tiny_mce_before_init', 'my_mce4_options' );


/**
 * Add custom Formats button
 */
if ( ! function_exists( 'wysiwyg_styleselect_button' ) ) {
	function wysiwyg_styleselect_button( $buttons ) {
		array_unshift( $buttons, 'styleselect' );
		// array_unshift( $buttons, 'fontselect' );
		return $buttons;
	}
	add_filter( 'mce_buttons_2', 'wysiwyg_styleselect_button' );
}

if ( ! function_exists( 'wysiwyg_style_formats' ) ) {
	function wysiwyg_style_formats( $settings ) {
		$settings['theme_advanced_blockformats'] = 'p,a,div,span,h1,h2,h3,h4,h5,h6,tr,';
		$style_formats = [
			array(
				'title'	=> __( 'Custom Elements', 'text_domain' ),
				'items'	=> [
					[
						'title'		=> __( 'Button', 'text_domain' ),
						'selector'	=> 'a',
						'classes'	=> 'btn'
					]
				],
			),
			array(
				'title'	=> __( 'Font Sizes', 'text_domain' ),
				'items'	=> [
					[
						'title'		=> __( 'Heading 1', 'text_domain' ),
						'selector'	=> 'p,a,h1,h2,h3,h4,h5,h6',
						'classes'	=> 'h1'
					],
					[
						'title'		=> __( 'Heading 2', 'text_domain' ),
						'selector'	=> 'p,a,h1,h2,h3,h4,h5,h6',
						'classes'	=> 'h2'
					],
					[
						'title'		=> __( 'Heading 3', 'text_domain' ),
						'selector'	=> 'p,a,h1,h2,h3,h4,h5,h6',
						'classes'	=> 'h3'
					],
					[
						'title'		=> __( 'Heading 4', 'text_domain' ),
						'selector'	=> 'p,a,h1,h2,h3,h4,h5,h6',
						'classes'	=> 'h4'
					],
					[
						'title'		=> __( 'Heading 5', 'text_domain' ),
						'selector'	=> 'p,a,h1,h2,h3,h4,h5,h6',
						'classes'	=> 'h5'
					],
					[
						'title'		=> __( 'Heading 6', 'text_domain' ),
						'selector'	=> 'p,a,h1,h2,h3,h4,h5,h6',
						'classes'	=> 'h6'
					],
				],
			),
			array(
				'title'	=> __( 'Font Family', 'text_domain' ),
				'items'	=> [
					[
						'title'		=> __( 'Didot', 'text_domain' ),
						'selector'	=> 'p,a,h1,h2,h3,h4,h5,h6',
						'classes'	=> 'didot-font'
					],
					[
						'title'		=> __( 'Inter', 'text_domain' ),
						'selector'	=> 'p,a,h1,h2,h3,h4,h5,h6',
						'classes'	=> 'inter-font'
					],

				],
			),
			array(
				'title'	=> __( 'Animation', 'text_domain' ),
				'items'	=> [
					[
						'title'		=> __( 'Reveal Text', 'text_domain' ),
						'selector'	=> 'p,a,h1,h2,h3,h4,h5,h6',
						'classes'	=> 'reveal-text'
					]
				],
			)
		];


		$settings['style_formats'] = json_encode( $style_formats );
		return $settings;
	}
	add_filter( 'tiny_mce_before_init', 'wysiwyg_style_formats' );
}

/**
 * Add default block palette colors in ACF color picker
 */
function klf_acf_input_admin_footer() {
	// Get the default block palette colors from theme.json or WordPress defaults.
	$default_palette = wp_get_global_settings( array( 'color', 'palette', 'theme' ) );

	// Initialize an array to hold the hex codes of the colors.
	$palette_colors = array();

	// Loop through the palette and add each color to the array.
	if ( ! empty( $default_palette ) && is_array( $default_palette ) ) {
		foreach ( $default_palette as $color ) {
			// Add the color to the palette array.
			$palette_colors[] = esc_js( $color['color'] );
		}
	}

	// Convert the palette array into a JavaScript-friendly format.
	$js_palette = json_encode( $palette_colors );
	?>

	<script type="text/javascript">
		(function($) {
			acf.add_filter('color_picker_args', function( args, $field ){
				// Use the dynamically generated palette colors
				args.palettes = <?php echo $js_palette; ?>;
				// Return the colors
				return args;
			});
		})(jQuery);
	</script>

	<?php
}
add_action( 'acf/input/admin_footer', 'klf_acf_input_admin_footer' );

add_theme_support( 'title-tag' );

function add_google_analytics() { ?>
	<!-- Google tag (gtag.js) -->
	<!-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-MQ7BGNLNX4"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'G-MQ7BGNLNX4');
	</script> -->
	<?php
}
add_action('wp_head', 'add_google_analytics');

/**
 * Preload Fonts
 */
function my_custom_theme_preload_fonts() {
    // List all the font files you want to preload here
    $fonts = array(
        'didot/didot-400-normal.otf',
        'inter/inter-600-normal.woff2',
        'inter/inter-400-normal.woff2',
		'inter/inter-300-normal.woff2'
    );

	//  $fonts = array(
    //     'didot/didot-400-normal.otf'
    // );

    // Get the base URL for the fonts directory
    $font_dir_url = get_template_directory_uri() . '/assets/fonts/';

    // Loop through the array and output a preload tag for each
    foreach ( $fonts as $font ) {
        $font_url  = $font_dir_url . $font;
        $extension = strtolower( pathinfo( $font, PATHINFO_EXTENSION ) );
        $type_map   = array(
            'woff2' => 'font/woff2',
            'woff'  => 'font/woff',
            'ttf'   => 'font/ttf',
            'otf'   => 'font/otf',
        );
        $font_type = isset( $type_map[ $extension ] ) ? $type_map[ $extension ] : 'font/' . $extension;
        echo '<link rel="preload" href="' . esc_url( $font_url ) . '" as="font" type="' . esc_attr( $font_type ) . '" crossorigin>' . "\n";
    }
}
add_action( 'wp_head', 'my_custom_theme_preload_fonts', 5 );


/**
 * Add WooCommerce Support
 */
function simple_block_add_woocommerce_support() {
	// Basic WooCommerce support
	add_theme_support( 'woocommerce' );

	// Enable WooCommerce product gallery features
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}

add_action( 'after_setup_theme', 'simple_block_add_woocommerce_support' );

/**
 * Prepend custom single product PHP layout to native post-content output.
 */
function simple_block_prepend_single_product_template_content( $content ) {
	if ( is_admin() || ! is_singular( 'product' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	if ( ! function_exists( 'wc_get_template_part' ) ) {
		return $content;
	}

	static $single_product_layout_rendered = false;

	if ( $single_product_layout_rendered ) {
		return $content;
	}

	$single_product_layout_rendered = true;

	ob_start();
	wc_get_template_part( 'content', 'single-product' );
	$custom_layout = (string) ob_get_clean();

	return $custom_layout . $content;
}
add_filter( 'the_content', 'simple_block_prepend_single_product_template_content', 9 );

/**
 * Enqueue custom WooCommerce single product styles.
 */
function simple_block_enqueue_woocommerce_single_product_assets() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}

	$single_product_css_path = get_template_directory() . '/woocommerce/single-product.css';

	wp_enqueue_style(
		'simple-block-woocommerce-single-product',
		get_template_directory_uri() . '/woocommerce/single-product.css',
		array(),
		file_exists( $single_product_css_path ) ? filemtime( $single_product_css_path ) : '1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'simple_block_enqueue_woocommerce_single_product_assets' );


// ENABLE WORDPRESS EDITOR IN WOOCOMMERCE start
add_filter( 'use_block_editor_for_post_type', 'activate_gutenberg_product', 10, 2 );
function activate_gutenberg_product( $can_edit, $post_type ) {
 if ( $post_type == 'product' ) { $can_edit = true; }
 return $can_edit;
}

add_filter( 'woocommerce_taxonomy_args_product_cat', 'enable_taxonomy_rest' );
add_filter( 'woocommerce_taxonomy_args_product_tag', 'enable_taxonomy_rest' );
function enable_taxonomy_rest( $args ) {
    $args['show_in_rest'] = true;
    return $args;
}

function simple_block_load_info_table_product_taxonomy_choices( $field ) {
	$field['choices'] = array();

	$taxonomies = get_object_taxonomies( 'product', 'objects' );
	$excluded_taxonomies = array(
		'product_type',
		'product_visibility',
		'product_shipping_class',
	);

	foreach ( $taxonomies as $taxonomy_slug => $taxonomy ) {
		if ( in_array( $taxonomy_slug, $excluded_taxonomies, true ) ) {
			continue;
		}

		if ( empty( $taxonomy->show_ui ) ) {
			continue;
		}

		$field['choices'][ $taxonomy_slug ] = $taxonomy->labels->singular_name ?: $taxonomy_slug;
	}

	return $field;
}
add_filter( 'acf/load_field/key=field_6878d23e3241e', 'simple_block_load_info_table_product_taxonomy_choices' );
// ENABLE WORDPRESS EDITOR IN WOOCOMMERCE end


add_filter( 'wp_generate_attachment_metadata', 'generate_lqip_base64_on_upload', 10, 2 );

function generate_lqip_base64_on_upload( $metadata, $attachment_id ) {
    // 1. Get the file path and ensure it is actually an image
    $file_path = get_attached_file( $attachment_id );
    $mime_type = get_post_mime_type( $attachment_id );

    // Skip non-images or SVGs (SVGs are already code and don't need blur-up)
    if ( ! $file_path || strpos( $mime_type, 'image/' ) !== 0 || $mime_type === 'image/svg+xml' ) {
        return $metadata;
    }

    // 2. Load the WordPress image editor
    $editor = wp_get_image_editor( $file_path );

    if ( ! is_wp_error( $editor ) ) {
        // 3. Shrink the image to a maximum of 20x20 pixels
        $editor->resize( 20, 20, false );
        $editor->set_quality( 40 ); // Low quality keeps the base64 string very short

        // 4. Capture the binary image data in memory (avoids writing temp files to disk)
        ob_start();
        $editor->stream( $mime_type );
        $image_data = ob_get_clean();

        // 5. Encode and save to post meta
        if ( $image_data ) {
            $base64 = base64_encode( $image_data );
            $base64_string = 'data:' . $mime_type . ';base64,' . $base64;

            update_post_meta( $attachment_id, '_lqip_base64', $base64_string );
        }
    }

    return $metadata;
}



// function trigger_lqip_batch_regeneration() {
//     // Only run if you are an admin and you add ?regenerate_lqip=1 to the admin URL
//     if ( ! is_admin() || ! current_user_can( 'manage_options' ) || ! isset( $_GET['regenerate_lqip'] ) ) {
//         return;
//     }

//     // Process 50 images per page load to prevent server timeouts
//     $batch_size = 50; 

//     $args = array(
//         'post_type'      => 'attachment',
//         'post_mime_type' => 'image',
//         'post_status'    => 'inherit',
//         'posts_per_page' => $batch_size,
//         'fields'         => 'ids',
//         'meta_query'     => array(
//             array(
//                 'key'     => '_lqip_base64',
//                 'compare' => 'NOT EXISTS', // Only grab images missing the base64 string
//             ),
//         ),
//     );

//     $attachments = new WP_Query( $args );

//     if ( ! $attachments->have_posts() ) {
//         wp_die( 'All done! Every image now has a base64 placeholder.' );
//     }

//     $count = 0;

//     foreach ( $attachments->posts as $attachment_id ) {
//         $file_path = get_attached_file( $attachment_id );
//         $mime_type = get_post_mime_type( $attachment_id );

//         // Skip missing files or SVGs
//         if ( ! $file_path || $mime_type === 'image/svg+xml' ) {
//             // Mark as processed so it doesn't loop forever
//             update_post_meta( $attachment_id, '_lqip_base64', 'skipped' );
//             continue; 
//         }

//         $editor = wp_get_image_editor( $file_path );

//         if ( ! is_wp_error( $editor ) ) {
//             $editor->resize( 20, 20, false );
//             $editor->set_quality( 40 );

//             ob_start();
//             $editor->stream( $mime_type );
//             $image_data = ob_get_clean();

//             if ( $image_data ) {
//                 $base64 = base64_encode( $image_data );
//                 $base64_string = 'data:' . $mime_type . ';base64,' . $base64;

//                 update_post_meta( $attachment_id, '_lqip_base64', $base64_string );
//                 $count++;
//             }
//         }
//     }

//     $remaining = $attachments->found_posts - $batch_size;
//     $remaining = $remaining > 0 ? $remaining : 0;

//     wp_die( "Success! Generated {$count} placeholders. There are roughly {$remaining} images left. <br><br><a href='" . admin_url( '?regenerate_lqip=1' ) . "'>Click here to process the next 50</a>" );
// }
// add_action( 'admin_init', 'trigger_lqip_batch_regeneration' );


add_filter( 'image_editor_output_format', 'convert_images_to_webp_on_upload' );

function convert_images_to_webp_on_upload( $formats ) {
    // Convert all JPEGs and PNGs to WebP for generated image sizes
    $formats['image/jpeg'] = 'image/webp';
    $formats['image/png']  = 'image/webp';
    
    return $formats;
}




function trigger_full_image_regeneration() {
    // Only run if you are an admin and add ?regenerate_all_images=1 to the URL
    if ( ! is_admin() || ! current_user_can( 'manage_options' ) || ! isset( $_GET['regenerate_all_images'] ) ) {
        return;
    }

    // Process 10 images per page load to prevent server timeouts!
    // Generating WebP sizes is heavy work.
    $batch_size = 10; 

    $args = array(
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'post_status'    => 'inherit',
        'posts_per_page' => $batch_size,
        'fields'         => 'ids',
        'meta_query'     => array(
            array(
                'key'     => '_lqip_base64',
                'compare' => 'NOT EXISTS', // We use the missing base64 string to know which images still need processing
            ),
        ),
    );

    $attachments = new WP_Query( $args );

    if ( ! $attachments->have_posts() ) {
        wp_die( 'All done! All old images now have WebP versions and base64 placeholders.' );
    }

    // We must include this core WordPress file to access the image generation functions
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    $count = 0;

    foreach ( $attachments->posts as $attachment_id ) {
        $file_path = get_attached_file( $attachment_id );

        // Ensure the file actually exists on the server
        if ( $file_path && file_exists( $file_path ) ) {
            
            // This core WP function does the magic:
            // 1. It generates the sub-sizes (saving them as WebP due to our earlier filter)
            // 2. It triggers the base64 script automatically because we hooked into this process earlier!
            $attach_data = wp_generate_attachment_metadata( $attachment_id, $file_path );
            wp_update_attachment_metadata( $attachment_id, $attach_data );
            
            $count++;
            
        } else {
            // File is missing from disk, skip it so we don't get stuck in an infinite loop
            update_post_meta( $attachment_id, '_lqip_base64', 'skipped' );
        }
    }

    $remaining = $attachments->found_posts - $batch_size;
    $remaining = $remaining > 0 ? $remaining : 0;

    wp_die( "Success! Regenerated {$count} images into WebP and base64. Roughly {$remaining} left. <br><br><a href='" . admin_url( '?regenerate_all_images=1' ) . "'>Click here to process the next 10</a>" );
}
add_action( 'admin_init', 'trigger_full_image_regeneration' );