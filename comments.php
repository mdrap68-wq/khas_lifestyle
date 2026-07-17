<?php
/**
 * Comments template.
 *
 * @package KHAS
 */

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<h3>
			<?php
			$khas_count = get_comments_number();
			printf( esc_html( _n( '%s دیدگاه', '%s دیدگاه', $khas_count, 'khas' ) ), esc_html( khas_fa_num( $khas_count ) ) );
			?>
		</h3>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 44,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php
	// Logged-in chip (replaces default "Logged in as..." line).
	if ( is_user_logged_in() ) {
		$cu     = wp_get_current_user();
		$cu_ini = function_exists( 'mb_substr' ) ? mb_substr( $cu->display_name, 0, 1 ) : substr( $cu->display_name, 0, 3 );
		?>
		<div class="khas-comment-loggedin">
			<span class="ava"><?php echo wp_kses_post( get_avatar( $cu->ID, 72 ) ); ?><?php echo esc_html( $cu_ini ); ?></span>
			<span>
				<?php printf( esc_html__( 'شما به‌عنوان %s وارد شده‌اید.', 'khas' ), '<strong>' . esc_html( $cu->display_name ) . '</strong>' ); ?>
				<a href="<?php echo esc_url( wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) ) ); ?>"><?php esc_html_e( 'خروج', 'khas' ); ?></a>
			</span>
		</div>
		<?php
	}

	comment_form();
	?>
</div>
