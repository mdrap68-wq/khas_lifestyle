<?php
/**
 * Numbered pagination.
 *
 * @package KHAS
 */
$links = paginate_links(
	array(
		'type'      => 'array',
		'prev_text' => '→',
		'next_text' => '←',
	)
);
if ( $links ) :
	?>
	<nav class="khas-pagination" aria-label="<?php esc_attr_e( 'صفحه‌بندی', 'khas' ); ?>">
		<?php
		foreach ( $links as $link ) {
			echo wp_kses_post( $link );
		}
		?>
	</nav>
	<?php
endif;
