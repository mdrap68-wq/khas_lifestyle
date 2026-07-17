<?php
/**
 * Main fallback template (blog index / search).
 *
 * @package KHAS
 */

get_header();
?>

<section class="khas-archive-head">
	<div class="inner">
		<?php if ( is_search() ) : ?>
			<p class="kicker"><?php esc_html_e( 'نتایج جستجو', 'khas' ); ?></p>
			<h1><?php echo esc_html( get_search_query() ); ?></h1>
		<?php else : ?>
			<p class="kicker"><?php esc_html_e( 'مجله خاص', 'khas' ); ?></p>
			<h1><?php esc_html_e( 'آخرین مقالات', 'khas' ); ?></h1>
		<?php endif; ?>
	</div>
</section>

<section class="khas-section">
	<div class="khas-container">
		<?php if ( have_posts() ) : ?>
			<div class="khas-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					khas_render_card( 'card' );
				endwhile;
				?>
			</div>
			<?php get_template_part( 'template-parts/pagination' ); ?>
		<?php else : ?>
			<div class="khas-empty">
				<h1><?php esc_html_e( 'چیزی پیدا نشد', 'khas' ); ?></h1>
				<p><?php esc_html_e( 'مطلبی مطابق درخواست شما یافت نشد.', 'khas' ); ?></p>
				<a class="khas-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'بازگشت به خانه', 'khas' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
