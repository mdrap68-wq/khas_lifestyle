<?php
/**
 * Category archive.
 *
 * @package KHAS
 */

get_header();
$term = get_queried_object();
?>

<section class="khas-archive-head">
	<div class="inner">
		<div class="top">
			<span class="icon"><?php echo esc_html( khas_category_icon( $term ) ); ?></span>
			<div>
				<p class="kicker"><?php esc_html_e( 'دسته‌بندی', 'khas' ); ?></p>
				<h1><?php single_cat_title(); ?></h1>
			</div>
		</div>
		<?php if ( category_description() ) : ?>
			<p class="desc"><?php echo wp_kses_post( category_description() ); ?></p>
		<?php endif; ?>
		<p class="count"><?php printf( esc_html__( '%s مقاله در این دسته منتشر شده است.', 'khas' ), esc_html( khas_fa_num( $term->count ) ) ); ?></p>
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
				<h1><?php esc_html_e( 'هنوز مقاله‌ای منتشر نشده', 'khas' ); ?></h1>
				<p><?php esc_html_e( 'به‌زودی محتوای جدیدی در این دسته اضافه خواهد شد.', 'khas' ); ?></p>
				<a class="khas-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'بازگشت به خانه', 'khas' ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
