<?php
/**
 * Static page template.
 *
 * @package KHAS
 */

get_header();

while ( have_posts() ) :
	the_post();
	$cover = get_the_post_thumbnail_url( get_the_ID(), 'khas-hero' );
	?>
	<article <?php post_class(); ?>>
		<?php if ( $cover ) : ?>
			<div class="khas-single-hero">
				<img class="cover" src="<?php echo esc_url( $cover ); ?>" alt="<?php the_title_attribute(); ?>" />
				<span class="mask"></span>
				<div class="body"><div class="wrap"><h1><?php the_title(); ?></h1></div></div>
			</div>
		<?php else : ?>
			<section class="khas-archive-head">
				<div class="inner"><h1><?php the_title(); ?></h1></div>
			</section>
		<?php endif; ?>

		<div class="khas-article">
			<div class="content"><?php the_content(); ?></div>
		</div>
	</article>
	<?php
	if ( comments_open() || get_comments_number() ) :
		echo '<div class="khas-comments">';
		comments_template();
		echo '</div>';
	endif;
endwhile;

get_footer();
