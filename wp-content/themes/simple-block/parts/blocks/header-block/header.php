<?php
/**
 * Block Name: Header Block
 *
 * This is the template that displays the Grid block.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ci-uikit
 **/

if ( isset( $block['data']['preview_image_help'] ) ) :    /* rendering in inserter preview  */
	echo '<img src="' . esc_url( get_template_directory_uri() ) . esc_attr( $block['data']['preview_image_help'] ) . '" style="width:100%; height:auto;">';

else : /* Rendering in editor body. */
	?>

	<div class="bottom-menu" data-uk-sticky="show-on-up: true; animation: uk-animation-slide-top; cls-active: uk-navbar-sticky;">
		<div class="container">
			<div class="bottom-menu-wrp">
				<?php if( get_field('header_logo', 'option') ): ?>
					<div class="site-branding">
						<a aria-label="Link do početne stranice" class="header-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<img alt="<?php echo get_field('header_logo', 'option')['alt']; ?>" src="<?php echo get_field('header_logo', 'option')['sizes']['medium'] ?>">
						</a>
					</div><!-- .site-branding -->
				<?php endif; ?>

				<div class="navigation-wrp">
					<?php echo do_blocks( '<!-- wp:navigation {"ref":6,"showSubmenuIcon":false,"overlayMenu":"never"} /-->' ); ?>
					<!-- <div class="search-wrp">
						<a class="uk-navbar-toggle" data-uk-search-icon href="#"></a>
						<div class="uk-drop" uk-drop="mode: click; pos: left-center; offset: 0">
							<form class="uk-search uk-search-navbar uk-width-1-1" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
								<label for="search"></label>
								<input class="uk-search-input" type="text" name="s" id="search" value="<?php the_search_query(); ?>" autofocus />
								<input class="search-btn" type="submit" alt="Search"/>
							</form>
						</div>
					</div> -->

				</div>

				<div class="open-menu">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</div><!-- mobile-menu-trigger -->
			</div>
		</div>

	</div>

<?php endif; ?>
