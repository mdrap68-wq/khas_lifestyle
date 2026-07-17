<?php
/**
 * Generic archive.
 *
 * @package KHAS
 */

get_header();
?>

<section class="khas-archive-head">
	<div class="inner">
		<div class="top">
			<div>
				<p class="kicker"><?php esc_html_e( 'آرشیو', 'khas' ); ?></p>
				<h1><?php the_archive_title(); ?></h1>
			</div>
		</div>
		<?php if ( get_the_archive_description() ) : ?>
			<p class="desc"><?php the_archive_description(); ?></p>
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
				<div class="big">∅</div>
				<h1><?php esc_html_e( 'چیزی پیدا نشد', 'khas' ); ?></h1>
				<a class="khas-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'بازگشت به خانه', 'khas' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
