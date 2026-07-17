<?php
/**
 * Single post.
 *
 * @package KHAS
 */

get_header();

while ( have_posts() ) :
	the_post();
	$cats  = get_the_category();
	$cat   = ! empty( $cats ) ? $cats[0] : null;
	$cover = get_the_post_thumbnail_url( get_the_ID(), 'khas-hero' );
	if ( ! $cover ) {
		$cover = 'https://images.pexels.com/photos/20285350/pexels-photo-20285350.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=1400';
	}
	?>

	<article <?php post_class(); ?>>
		<div class="khas-single-hero">
			<img class="cover" src="<?php echo esc_url( $cover ); ?>" alt="<?php the_title_attribute(); ?>" />
			<span class="mask"></span>
			<div class="body">
				<div class="wrap">
					<?php if ( $cat ) : ?>
						<span class="khas-eyebrow" style="color:var(--c-gold-2);"><?php echo esc_html( $cat->name ); ?></span>
					<?php endif; ?>
					<h1><?php the_title(); ?></h1>
					<div class="metaline">
						<span><?php echo esc_html( khas_fa_num( khas_reading_time() ) ); ?> دقیقه مطالعه</span>
						<span class="sep" style="width:3px;height:3px;border-radius:50%;background:rgba(255,255,255,0.3);"></span>
						<span><?php echo esc_html( get_the_author() ); ?></span>
						<span class="sep" style="width:3px;height:3px;border-radius:50%;background:rgba(255,255,255,0.3);"></span>
						<span><?php echo esc_html( get_the_date() ); ?></span>
					</div>
				</div>
			</div>
		</div>

		<div class="khas-article">
			<?php if ( has_excerpt() ) : ?>
				<p class="lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>

			<div class="content">
				<?php
				the_content();
				wp_link_pages(
					array(
						'before' => '<div class="khas-pagination">',
						'after'  => '</div>',
					)
				);
				?>
			</div>

			<div class="navrow">
				<?php if ( $cat ) : ?>
					<a class="more" href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
						<?php printf( esc_html__( 'بیشتر در «%s» ←', 'khas' ), esc_html( $cat->name ) ); ?>
					</a>
				<?php else : ?>
					<span></span>
				<?php endif; ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( '→ بازگشت به خانه', 'khas' ); ?></a>
			</div>

			<?php /* Author bio box */ ?>
			<?php
			$author_id   = get_the_author_meta( 'ID' );
			$author_name = get_the_author();
			$author_bio  = get_the_author_meta( 'description' );
			$author_ini  = function_exists( 'mb_substr' ) ? mb_substr( $author_name, 0, 1 ) : substr( $author_name, 0, 3 );
			$avatar      = $author_id ? get_avatar( $author_id, 176 ) : '';
			if ( ! $author_bio ) {
				$author_bio = sprintf( __( '%s نویسنده‌ی مجله‌ی خاص است و در زمینه‌ی %s فعالیت می‌کند.', 'khas' ), $author_name, $cat ? $cat->name : __( 'سبک زندگی', 'khas' ) );
			}
			?>
			<div class="khas-author-box">
				<div class="khas-author-box__ava">
					<?php echo wp_kses_post( $avatar ); ?>
					<span aria-hidden="true"><?php echo esc_html( $author_ini ); ?></span>
				</div>
				<div class="khas-author-box__info">
					<div class="khas-author-box__label"><?php esc_html_e( 'درباره‌ی نویسنده', 'khas' ); ?></div>
					<h4 class="khas-author-box__name"><?php echo esc_html( $author_name ); ?></h4>
					<p class="khas-author-box__bio"><?php echo esc_html( $author_bio ); ?></p>
					<a class="khas-author-box__link" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
						<?php esc_html_e( 'مشاهده‌ی همه‌ی نوشته‌ها', 'khas' ); ?>
						<span>←</span>
					</a>
				</div>
			</div>
		</div>
	</article>

	<?php
	if ( $cat ) :
		$related = new WP_Query(
			array(
				'category__in'        => array( $cat->term_id ),
				'post__not_in'        => array( get_the_ID() ),
				'posts_per_page'      => 3,
				'ignore_sticky_posts' => 1,
			)
		);
		if ( $related->have_posts() ) :
			?>
			<section class="khas-section khas-section--warm" style="padding-top:80px;">
				<div class="khas-container">
					<div class="khas-section-head">
						<span class="diamond"></span>
						<h2><?php esc_html_e( 'مقالات مرتبط', 'khas' ); ?></h2>
						<span class="line"></span>
					</div>
					<div class="khas-grid">
						<?php
						while ( $related->have_posts() ) :
							$related->the_post();
							khas_render_card( 'card' );
						endwhile;
						?>
					</div>
				</div>
			</section>
			<?php
			wp_reset_postdata();
		endif;
	endif;

	if ( comments_open() || get_comments_number() ) :
		echo '<div class="khas-comments">';
		comments_template();
		echo '</div>';
	endif;

endwhile;

get_footer();
