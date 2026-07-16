<?php

$title = $args['title'] ?? '';
$content  = $args['content'] ?? '';
$custom_classes = $args['custom_classes'] ?? '';
$link = $args['link'] ?? '';
$show_numbers = $args['show_numbers'] ?? '';
$number = $args['number'] ?? '';


wp_enqueue_style( 'component-big-card-style', get_template_directory_uri() . '/components/cards/big-card/big-card.css' );
wp_enqueue_script( 'component-big-card-script', get_template_directory_uri() . '/components/cards/big-card/big-card.js', array(), '1.0', array( 'strategy' => 'defer' ) );

?>

<div class="custom-big-card-component animation-fade-item <?php echo $custom_classes; ?>">
	<svg class="big-card-border-draw" preserveAspectRatio="none">
		<defs>
			<linearGradient id="big-cardBorderGradient" x1="0%" y1="0%" x2="0%" y2="100%">
				<stop offset="0%" stop-color="#A4551B" />
				<stop offset="100%" stop-color="#C5A279" />
			</linearGradient>
		</defs>
		<path class="big-card-border-path big-card-border-path--right" />
		<path class="big-card-border-path big-card-border-path--left" />
	</svg>
	<div class="custom-big-card-content">
		<?php
		if($show_numbers && $number){ ?>
			<div class="big-card-number">
				<?php echo $number; ?>
			</div>
		<?php } ?>
		<div>
			<?php if($title){ ?>
				<h3 class="h4 big-card-title"><?php echo esc_html( $title ); ?></h3>
			<?php }
			if ( $content ){ ?>
				<p class="big-card-content"><?php echo esc_html( $content ); ?></p>
			<?php } ?>
		</div>
		<?php if( $link ):
			$link_url = $link['url'];
			$link_title = $link['title'];
			$link_target = $link['target'] ? $link['target'] : '_self'; ?>
				<a class="big-card-link" href="<?php echo esc_url( $link_url ); ?>" target="	<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?></a>
		<?php endif; ?>
	</div>
</div>