<?php
/**
 * Block Name: Info Table
 *
 * This is the template that displays the Info Table block.
 *
 * @package ci-uikit
 **/

if ( isset( $block['anchor'] ) ) {
	$block_id = esc_attr( $block['anchor'] );
} else {
	$block_id = 'ci-info-table-' . $block['id'];
}

$main_block_class = 'ci-info-table-block ci-block ci-has-background';
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

$remove_bottom_margin = (bool) get_field( 'remove_bottom_margin' );
if ( $remove_bottom_margin ) {
	$main_block_class .= ' ci-no-bottom-margin';
}

if ( isset( $block['data']['preview_image_help'] ) ) :
	echo '<img src="' . esc_url( get_template_directory_uri() ) . esc_attr( $block['data']['preview_image_help'] ) . '" style="width:100%; height:auto;">';

else :
	include __DIR__ . '/../block-parts/background-and-text-color-block.php';

	$current_product = null;
	if ( function_exists( 'wc_get_product' ) ) {
		if ( isset( $GLOBALS['product'] ) && $GLOBALS['product'] instanceof WC_Product ) {
			$current_product = $GLOBALS['product'];
		} elseif ( is_singular( 'product' ) ) {
			$current_product = wc_get_product( get_the_ID() );
		}
	}

	$items_source = get_field( 'items_source' ) ?: 'manual';
	$table_title = get_field( 'info_table_title' );
	$table_rows = array();

	if ( 'auto' === $items_source && $current_product instanceof WC_Product ) {
		$product_attributes = $current_product->get_attributes();

			foreach ( $product_attributes as $attribute ) {
				if ( ! $attribute->get_visible() ) {
					continue;
				}

				$attribute_label = wc_attribute_label( $attribute->get_name() );
				$attribute_value = '';

				if ( $attribute->is_taxonomy() ) {
					$terms = wc_get_product_terms( $current_product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
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
					$table_rows[] = array(
						'label' => $attribute_label,
						'value' => $attribute_value,
					);
				}
			}
	} else {
		$manual_items = get_field( 'info_table_items' );
		if ( ! empty( $manual_items ) && is_array( $manual_items ) ) {
			foreach ( $manual_items as $item ) {
				$label = isset( $item['label'] ) ? $item['label'] : '';
				$value = isset( $item['value'] ) ? $item['value'] : '';
				if ( '' === trim( (string) $label ) && '' === trim( (string) $value ) ) {
					continue;
				}

				$table_rows[] = array(
					'label' => $label,
					'value' => $value,
				);
			}
		}
	}

	$steps_group = get_field( 'steps_group' );
	$steps_title = isset( $steps_group['title'] ) ? $steps_group['title'] : '';
	$steps_items = isset( $steps_group['items'] ) && is_array( $steps_group['items'] ) ? $steps_group['items'] : array();
	$show_additional_data = (bool) get_field( 'show_additional_data' );
	$additional_cards = get_field( 'additional_data_cards' );
	$resolved_additional_cards = array();

	if ( $show_additional_data && ! empty( $additional_cards ) && is_array( $additional_cards ) ) {
		foreach ( $additional_cards as $card ) {
			$card_title = isset( $card['title'] ) ? $card['title'] : '';
			$card_source = isset( $card['card_source'] ) ? $card['card_source'] : 'manual';
			$card_taxonomy = isset( $card['card_taxonomy'] ) ? $card['card_taxonomy'] : '';
			$manual_content = isset( $card['manual_content'] ) ? $card['manual_content'] : '';
			$card_terms = array();

			if ( 'auto' === $card_source && $current_product instanceof WC_Product && $card_taxonomy && taxonomy_exists( $card_taxonomy ) ) {
				$terms = get_the_terms( $current_product->get_id(), $card_taxonomy );
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					$card_terms = array_values(
						array_filter(
							array_map(
								static function ( $term ) {
									return isset( $term->name ) ? $term->name : '';
								},
								$terms
							)
						)
					);
				}
			}

			if ( '' === trim( (string) $card_title ) && empty( $card_terms ) && '' === trim( wp_strip_all_tags( (string) $manual_content ) ) ) {
				continue;
			}

			$resolved_additional_cards[] = array(
				'title' => $card_title,
				'source' => $card_source,
				'terms' => $card_terms,
				'manual_content' => $manual_content,
			);
		}
	}
	?>

	<section id="<?php echo esc_attr( $block_id ); ?>" <?php echo $wrapper_attributes; ?>>
		<?php if ( $bg_image_id ) : ?>
			<?php
			echo wp_get_attachment_image(
				$bg_image_id,
				'full-hero-size',
				false,
				array(
					'class' => 'section-background-image',
					'alt'   => $bg_image_alt,
				)
			);
			?>
		<?php endif; ?>

		<?php if ( get_field( 'block_background_image' ) && get_field( 'block_background_color' ) ) : ?>
			<div class="section-img-overlay" style="background-color: <?php echo esc_attr( get_field( 'block_background_color' ) ); ?>"></div>
		<?php endif; ?>

		<div class="container" <?php include __DIR__ . '/../block-parts/animation-block.php'; ?>>
			<div class="info-table-grid uk-grid-large" data-uk-grid>
                <?php if ( ! empty( $steps_items ) ) : ?>
                    <div class="uk-width-1-2@m">
                        <?php if ( $steps_title ) : ?>
                            <h2 class="info-steps-title animation-fade-item" <?php echo $duration; ?>><?php echo esc_html( $steps_title ); ?></h2>
                        <?php endif; ?>

                        <?php if ( ! empty( $steps_items ) ) : ?>
                            <ol class="info-steps-list">
                                <?php foreach ( $steps_items as $index => $step ) : ?>
                                    <?php
                                    $step_title = isset( $step['title'] ) ? $step['title'] : '';
                                    $step_attrs_raw = isset( $step['attrs'] ) ? $step['attrs'] : '';
                                    $step_content = isset( $step['content'] ) ? $step['content'] : '';
                                    $step_attrs = array_filter( array_map( 'trim', explode( ',', (string) $step_attrs_raw ) ) );
                                    ?>
                                    <li class="info-step-item animation-fade-item" <?php echo $duration; ?>>
                                        <div class="info-step-number"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></div>
                                        <div class="info-step-content rm-last-child-margin">
                                            <?php if ( $step_title ) : ?>
                                                <h2 class="info-step-title"><?php echo esc_html( $step_title ); ?></h2>
                                            <?php endif; ?>

                                            <?php if ( ! empty( $step_attrs ) ) : ?>
                                                <div class="info-step-attrs">
                                                    <?php foreach ( $step_attrs as $attr ) : ?>
                                                        <span class="info-step-chip"><?php echo esc_html( $attr ); ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ( $step_content ) : ?>
                                                <div class="info-step-text"><?php echo wp_kses_post( wpautop( $step_content ) ); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
				<div class="uk-width-expand">
					<div class="info-table-panel animation-fade-item" <?php echo $duration; ?> data-uk-sticky="end: !.info-table-grid; offset: 50">
						<?php if ( $table_title ) : ?>
							<h2 class="info-table-title"><?php echo esc_html( $table_title ); ?></h2>
						<?php endif; ?>

						<?php if ( ! empty( $table_rows ) ) : ?>
							<ul class="info-table-list">
								<?php foreach ( $table_rows as $row ) : ?>
									<li class="info-table-row">
										<span class="info-table-label"><?php echo esc_html( $row['label'] ); ?></span>
										<span class="info-table-value"><?php echo esc_html( $row['value'] ); ?></span>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php else : ?>
							<p class="info-table-empty"><?php esc_html_e( 'No items to display yet.', 'simple-block' ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<?php if ( ! empty( $resolved_additional_cards ) ) : ?>
				<div class="info-additional-grid uk-grid-large" data-uk-grid>
					<?php foreach ( $resolved_additional_cards as $additional_card ) : ?>
						<div class="uk-width-1-2@m animation-fade-item" <?php echo $duration; ?>>
							<div class="info-additional-card rm-last-child-margin">
								<?php if ( '' !== trim( (string) $additional_card['title'] ) ) : ?>
									<h3 class="info-additional-card-title"><?php echo esc_html( $additional_card['title'] ); ?></h3>
								<?php endif; ?>

								<?php if ( 'auto' === $additional_card['source'] && ! empty( $additional_card['terms'] ) ) : ?>
									<p class="info-additional-card-terms"><?php echo esc_html( implode( ' • ', $additional_card['terms'] ) ); ?></p>
								<?php elseif ( '' !== trim( wp_strip_all_tags( (string) $additional_card['manual_content'] ) ) ) : ?>
									<div class="info-additional-card-content"><?php echo wp_kses_post( $additional_card['manual_content'] ); ?></div>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>
