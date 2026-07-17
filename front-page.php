<?php
/**
 * Front page — extended editorial layout.
 *
 * @package KHAS
 */

get_header();

$khas_sticky   = get_option( 'sticky_posts' );
$khas_feat_ids = array();

/* ---------- HERO ---------- */
$hero_query = new WP_Query(
	array(
		'posts_per_page'      => 1,
		'ignore_sticky_posts' => empty( $khas_sticky ) ? 1 : 0,
		'post__in'            => ! empty( $khas_sticky ) ? $khas_sticky : array(),
	)
);
$hero_post = null;
if ( $hero_query->have_posts() ) :
	$hero_query->the_post();
	$hero_post       = get_the_ID();
	$khas_feat_ids[] = $hero_post;
	$hero_img        = get_the_post_thumbnail_url( $hero_post, 'khas-hero' );
	if ( ! $hero_img ) {
		$hero_img = 'https://images.pexels.com/photos/2174625/pexels-photo-2174625.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=1400';
	}
	?>
	<section class="khas-hero">
		<img class="khas-hero__bg" src="<?php echo esc_url( $hero_img ); ?>" alt="<?php the_title_attribute(); ?>" />
		<span class="khas-hero__mask"></span>
		<span class="khas-hero__mask2"></span>
		<div class="khas-hero__inner">
			<div class="khas-hero__content">
				<span class="khas-eyebrow"><?php esc_html_e( 'مجله سبک زندگی', 'khas' ); ?></span>
				<h1><?php esc_html_e( 'زندگی خاص،', 'khas' ); ?><br/><?php esc_html_e( 'انتخاب‌های هوشمندانه', 'khas' ); ?></h1>
				<p><?php esc_html_e( 'راهنماهای کاربردی، بررسی‌های تخصصی و ایده‌هایی برای ساختن یک زندگی بهتر و آرام‌تر.', 'khas' ); ?></p>
				<a class="khas-btn" href="<?php the_permalink(); ?>">
					<?php esc_html_e( 'شروع کاوش', 'khas' ); ?>
					<span class="arrow">←</span>
				</a>
			</div>
		</div>
	</section>
	<?php
	wp_reset_postdata();
endif;
?>

<?php /* ---------- CATEGORY CIRCLES ---------- */ ?>
<section class="khas-cats">
	<div class="khas-container">
		<div class="khas-cats__row">
			<?php
			$circle_cats = get_categories(
				array(
					'orderby'    => 'count',
					'order'      => 'DESC',
					'number'     => 10,
					'hide_empty' => false,
				)
			);
			foreach ( $circle_cats as $cc ) :
				?>
				<a class="khas-cat" href="<?php echo esc_url( get_category_link( $cc->term_id ) ); ?>">
					<span class="icon"><?php echo khas_category_svg( $cc ); // phpcs:ignore ?></span>
					<span class="label"><?php echo esc_html( $cc->name ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php /* ---------- FEATURED ---------- */ ?>
<?php if ( false ) : /* legacy stats removed */ ?>
<section class="khas-stats" hidden>
	<div class="khas-container">
		<div class="khas-stats__grid">
			<div class="khas-stats__item">
				<div class="num"><span class="plus">+</span><?php echo esc_html( khas_fa_num( max( 250, wp_count_posts()->publish ) ) ); ?></div>
				<div class="label"><?php esc_html_e( 'مقاله منتشر شده', 'khas' ); ?></div>
			</div>
			<div class="khas-stats__item">
				<div class="num"><?php echo esc_html( khas_fa_num( max( 8, wp_count_terms( array( 'taxonomy' => 'category', 'hide_empty' => false ) ) ) ) ); ?></div>
				<div class="label"><?php esc_html_e( 'دسته تخصصی', 'khas' ); ?></div>
			</div>
			<div class="khas-stats__item">
				<div class="num"><?php echo esc_html( khas_fa_num( max( 12, count_users()['total_users'] ) ) ); ?></div>
				<div class="label"><?php esc_html_e( 'نویسنده متخصص', 'khas' ); ?></div>
			</div>
			<div class="khas-stats__item">
				<div class="num"><span class="plus">+</span>۵٬۰۰</div>
				<div class="label"><?php esc_html_e( 'خواننده ماهانه', 'khas' ); ?></div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php /* ---------- FEATURED ---------- */ ?>
<section class="khas-section" style="padding-bottom:64px;">
	<div class="khas-container">
		<div class="khas-section-head">
			<span class="diamond"></span>
			<h2><?php esc_html_e( 'مقالات ویژه', 'khas' ); ?></h2>
			<span class="line"></span>
		</div>

		<?php
		$feat_side = new WP_Query(
			array(
				'posts_per_page'      => 2,
				'ignore_sticky_posts' => 1,
				'post__not_in'        => $khas_feat_ids,
			)
		);
		?>
		<div class="khas-featured-grid">
			<div class="khas-feature-side-wrap">
				<?php
				if ( $feat_side->have_posts() ) {
					while ( $feat_side->have_posts() ) {
						$feat_side->the_post();
						$khas_feat_ids[] = get_the_ID();
						khas_render_card( 'feature-side' );
					}
					wp_reset_postdata();
				}
				?>
			</div>
			<?php
			$big = new WP_Query( array( 'p' => $hero_post ) );
			if ( $big->have_posts() ) {
				while ( $big->have_posts() ) {
					$big->the_post();
					khas_render_card( 'feature-big' );
				}
				wp_reset_postdata();
			}
			?>
		</div>
	</div>
</section>

<?php /* ---------- EDITOR'S PICK ---------- */ ?>
<?php
$pick_query = new WP_Query(
	array(
		'posts_per_page'      => 1,
		'ignore_sticky_posts' => 1,
		'post__not_in'        => $khas_feat_ids,
	)
);
if ( $pick_query->have_posts() ) :
	$pick_query->the_post();
	$khas_feat_ids[] = get_the_ID();
	$pick_img        = get_the_post_thumbnail_url( get_the_ID(), 'khas-hero' );
	if ( ! $pick_img ) {
		$pick_img = 'https://images.pexels.com/photos/20285350/pexels-photo-20285350.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=1200';
	}
	$pick_cats = get_the_category();
	$pick_cat  = ! empty( $pick_cats ) ? $pick_cats[0] : null;
	?>
	<section class="khas-section" style="padding-top:24px;">
		<div class="khas-container">
			<div class="khas-section-head">
				<span class="diamond"></span>
				<h2><?php esc_html_e( 'انتخاب سردبیر', 'khas' ); ?></h2>
				<span class="line"></span>
			</div>
			<a class="khas-pick" href="<?php the_permalink(); ?>">
				<div class="khas-pick__img">
					<img src="<?php echo esc_url( $pick_img ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
				</div>
				<div class="khas-pick__body">
					<span class="khas-eyebrow"><?php esc_html_e( 'مقاله هفته', 'khas' ); ?></span>
					<h3><?php the_title(); ?></h3>
					<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 36, '…' ) ); ?></p>
					<span class="khas-btn"><?php esc_html_e( 'مطالعه کامل', 'khas' ); ?> <span class="arrow">←</span></span>
					<div class="meta">
						<?php if ( $pick_cat ) : ?><span><?php echo esc_html( $pick_cat->name ); ?></span><span class="sep"></span><?php endif; ?>
						<span><?php echo esc_html( get_the_author() ); ?></span>
						<span class="sep"></span>
						<span><?php echo esc_html( khas_fa_num( khas_reading_time() ) ); ?> دقیقه مطالعه</span>
					</div>
				</div>
			</a>
		</div>
	</section>
	<?php
	wp_reset_postdata();
endif;
?>

<?php /* ---------- LATEST + POPULAR ---------- */ ?>
<section class="khas-section khas-section--warm">
	<div class="khas-container">
		<div class="khas-two-col">
			<div>
				<div class="khas-section-head">
					<span class="diamond"></span>
					<h2><?php esc_html_e( 'آخرین مقالات', 'khas' ); ?></h2>
					<span class="line"></span>
				</div>
				<div class="khas-latest-grid">
					<?php
					$latest = new WP_Query(
						array(
							'posts_per_page'      => 3,
							'ignore_sticky_posts' => 1,
							'post__not_in'        => $khas_feat_ids,
						)
					);
					if ( $latest->have_posts() ) {
						while ( $latest->have_posts() ) {
							$latest->the_post();
							khas_render_card( 'latest' );
						}
						wp_reset_postdata();
					}
					?>
				</div>
			</div>

			<div class="khas-popular-col">
				<div class="khas-section-head">
					<span class="diamond"></span>
					<h2><?php esc_html_e( 'راهنماهای محبوب', 'khas' ); ?></h2>
					<span class="line"></span>
				</div>
				<ol class="khas-popular">
					<?php
					$popular = new WP_Query(
						array(
							'posts_per_page'      => 5,
							'ignore_sticky_posts' => 1,
							'orderby'             => 'comment_count',
							'order'               => 'DESC',
						)
					);
					$idx = 0;
					if ( $popular->have_posts() ) {
						while ( $popular->have_posts() ) {
							$popular->the_post();
							$idx++;
							$khas_num = ( $idx < 10 ? '۰' : '' ) . khas_fa_num( $idx );
							?>
							<li>
								<a href="<?php the_permalink(); ?>">
									<span class="num"><?php echo esc_html( $khas_num ); ?></span>
									<div>
										<h4><?php the_title(); ?></h4>
										<span class="rt">
											<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7.5v4.8l3 1.9"/></svg>
											<?php echo esc_html( khas_fa_num( khas_reading_time() ) ); ?> دقیقه مطالعه
										</span>
									</div>
								</a>
							</li>
							<?php
						}
						wp_reset_postdata();
					}
					?>
				</ol>
			</div>
		</div>
	</div>
</section>

<?php /* ---------- POPULAR CATEGORIES ---------- */ ?>
<section class="khas-section" style="padding-top:24px;">
	<div class="khas-container">
		<div class="khas-section-head">
			<span class="diamond"></span>
			<h2><?php esc_html_e( 'دسته‌بندی‌های پرطرفدار', 'khas' ); ?></h2>
			<span class="line"></span>
		</div>
		<div class="khas-popcats">
			<?php
			$pop_cats = get_categories(
				array(
					'orderby'    => 'count',
					'order'      => 'DESC',
					'number'     => 4,
					'hide_empty' => true,
				)
			);
			foreach ( $pop_cats as $pc ) :
				$last_in_cat = get_posts(
					array(
						'category'       => $pc->term_id,
						'posts_per_page' => 1,
						'fields'         => 'ids',
					)
				);
				$pc_img = '';
				if ( $last_in_cat ) {
					$pc_img = get_the_post_thumbnail_url( $last_in_cat[0], 'khas-card' );
				}
				if ( ! $pc_img ) {
					$pc_img = 'https://images.pexels.com/photos/20285350/pexels-photo-20285350.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=800&w=640';
				}
				?>
				<a class="khas-popcat" href="<?php echo esc_url( get_category_link( $pc->term_id ) ); ?>">
					<img src="<?php echo esc_url( $pc_img ); ?>" alt="<?php echo esc_attr( $pc->name ); ?>" loading="lazy" />
					<span class="mask"></span>
					<div class="body">
						<div class="count"><?php echo esc_html( khas_fa_num( $pc->count ) ); ?> <?php esc_html_e( 'مقاله', 'khas' ); ?></div>
						<h3><?php echo esc_html( $pc->name ); ?></h3>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php /* ---------- NEWSLETTER ---------- */ ?>
<section class="khas-section" id="khas-newsletter" style="padding-top:24px;">
	<div class="khas-container">
		<div class="khas-news">
			<div class="khas-news__body">
				<h2><?php esc_html_e( 'الهام و ایده‌های خاص را در ایمیل خود دریافت کنید', 'khas' ); ?></h2>
				<p><?php esc_html_e( 'جدیدترین مقالات، راهنماها و پیشنهادهای مخصوص را رایگان در ایمیل خود دریافت کنید.', 'khas' ); ?></p>
				<form class="khas-news__form">
					<input type="email" required placeholder="<?php esc_attr_e( 'ایمیل خود وارد کنید', 'khas' ); ?>" />
					<button type="submit"><?php esc_html_e( 'عضویت در خبرنامه', 'khas' ); ?></button>
				</form>
				<p class="khas-news__msg" role="status" aria-live="polite"></p>
				<p class="khas-news__note">
					<?php esc_html_e( 'به دنبال دسترسی کامل هستید؟', 'khas' ); ?>
					<a href="<?php echo esc_url( khas_membership_url() ); ?>" style="color:#fff;font-weight:700;text-decoration:underline;">
						<?php esc_html_e( 'اشتراک ویژه تهیه کنید', 'khas' ); ?>
					</a>
				</p>
			</div>
			<div class="khas-news__media">
				<img class="khas-news__img" src="https://images.pexels.com/photos/6373305/pexels-photo-6373305.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=520&w=900" alt="<?php esc_attr_e( 'مجله خاص', 'khas' ); ?>" loading="lazy" />
			</div>
		</div>
	</div>
</section>

<?php /* ---------- AUTHORS ---------- */ ?>
<?php
$khas_authors = array(
	array(
		'name' => 'نرگس امیری',
		'role' => 'سردبیر سبک زندگی',
		'bio'  => 'نویسنده و پژوهشگر حوزه‌ی طراحی و آرامش؛ علاقه‌مند به روایت‌های کوتاه از زندگی روزمره.',
		'img'  => 'https://images.pexels.com/photos/3756985/pexels-photo-3756985.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=200&w=200',
	),
	array(
		'name' => 'مانا یوسفی',
		'role' => 'کارشناس مد و زیبایی',
		'bio'  => 'استایلیست و روزنامه‌نگار مد؛ دنبال‌کننده‌ی ترندهای پایدار و زیبایی مینیمال.',
		'img'  => 'https://images.pexels.com/photos/415829/pexels-photo-415829.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=200&w=200',
	),
	array(
		'name' => 'هومن رستمی',
		'role' => 'خبرنگار سفر',
		'bio'  => 'عکاس و سفرنامه‌نویس؛ روایتگر فرهنگ‌ها و شهرهای فراموش‌شده‌ی جهان.',
		'img'  => 'https://images.pexels.com/photos/2379004/pexels-photo-2379004.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=200&w=200',
	),
	array(
		'name' => 'سارا کاظمی',
		'role' => 'متخصص تکنولوژی',
		'bio'  => 'تحلیل‌گر فناوری و سبک زندگی دیجیتال؛ نویسنده‌ی راهنماهای کاربردی برای زندگی هوشمند.',
		'img'  => 'https://images.pexels.com/photos/1181686/pexels-photo-1181686.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=200&w=200',
	),
);
?>
<section class="khas-section khas-section--warm">
	<div class="khas-container">
		<div class="khas-section-head">
			<span class="diamond"></span>
			<h2><?php esc_html_e( 'نویسندگان خاص', 'khas' ); ?></h2>
			<span class="line"></span>
		</div>
		<div class="khas-authors">
			<?php foreach ( $khas_authors as $a ) : ?>
				<div class="khas-author">
					<div class="ava">
						<img src="<?php echo esc_url( $a['img'] ); ?>" alt="<?php echo esc_attr( $a['name'] ); ?>" loading="lazy" />
					</div>
					<h4><?php echo esc_html( $a['name'] ); ?></h4>
					<div class="role"><?php echo esc_html( $a['role'] ); ?></div>
					<p><?php echo esc_html( $a['bio'] ); ?></p>
					<div class="social">
						<a href="<?php echo esc_url( khas_social_url( 'instagram' ) ); ?>" target="_blank" rel="noreferrer" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/></svg></a>
						<a href="<?php echo esc_url( khas_social_url( 'twitter' ) ); ?>" target="_blank" rel="noreferrer" aria-label="Twitter"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 5.8c-.7.3-1.5.6-2.3.7.8-.5 1.5-1.3 1.8-2.3-.8.5-1.7.8-2.6 1a4 4 0 0 0-6.8 3.6A11.3 11.3 0 0 1 3.9 4.6a4 4 0 0 0 1.2 5.3c-.6 0-1.2-.2-1.8-.5v.1a4 4 0 0 0 3.2 3.9c-.6.2-1.2.2-1.8.1a4 4 0 0 0 3.7 2.8A8 8 0 0 1 2 18a11.3 11.3 0 0 0 6.1 1.8c7.3 0 11.4-6.1 11.4-11.4v-.5c.8-.6 1.5-1.3 2-2.1z"/></svg></a>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php /* ---------- INSTAGRAM ---------- */ ?>
<section class="khas-section" style="padding-top:24px;">
	<div class="khas-container">
		<div class="khas-section-head">
			<span class="diamond"></span>
			<h2><?php esc_html_e( 'در اینستاگرام خاص', 'khas' ); ?></h2>
			<span class="line"></span>
		</div>
		<div class="khas-ig-grid">
			<a class="khas-ig-intro" href="<?php echo esc_url( khas_social_url( 'instagram' ) ); ?>" target="_blank" rel="noreferrer">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.3" cy="6.8" r="0.9" fill="currentColor" stroke="none"/></svg>
				<b><?php esc_html_e( 'ما را دنبال کنید', 'khas' ); ?></b>
				<small>@khas.lifestyle</small>
			</a>
			<?php
			$khas_ig_extra = array(
				'https://images.pexels.com/photos/9125016/pexels-photo-9125016.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=400&w=400',
				'https://images.pexels.com/photos/8128872/pexels-photo-8128872.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=400&w=400',
				'https://images.pexels.com/photos/29904622/pexels-photo-29904622.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=400&w=400',
				'https://images.pexels.com/photos/17307177/pexels-photo-17307177.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=400&w=400',
				'https://images.pexels.com/photos/35976902/pexels-photo-35976902.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=400&w=400',
				'https://images.pexels.com/photos/8755133/pexels-photo-8755133.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=400&w=400',
				'https://images.pexels.com/photos/14781780/pexels-photo-14781780.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=400&w=400',
			);
			$khas_ig_url = khas_social_url( 'instagram' );
			foreach ( $khas_ig_extra as $src ) :
				?>
				<a class="khas-ig-card" href="<?php echo esc_url( $khas_ig_url ); ?>" target="_blank" rel="noreferrer" aria-label="<?php esc_attr_e( 'اینستاگرام خاص', 'khas' ); ?>">
					<img src="<?php echo esc_url( $src ); ?>" alt="" loading="lazy" />
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
get_footer();
