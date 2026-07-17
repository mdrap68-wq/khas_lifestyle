<?php
/**
 * Footer template.
 *
 * @package KHAS
 */
$khas_footer_cats = get_categories(
	array(
		'orderby'    => 'count',
		'order'      => 'DESC',
		'number'     => 5,
		'hide_empty' => true,
	)
);
?>

<footer class="khas-footer">
	<div class="khas-footer__inner">
		<div class="khas-footer__cols">

			<?php /* BRAND — در RTL ستون اول = سمت راست */ ?>
			<div class="brand">
				<a class="khas-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<div class="khas-logo__text">
						<b>KHAS</b>
						<i>خاص</i>
					</div>
					<img class="khas-logo__symbol" src="<?php echo esc_url( get_template_directory_uri() . '/assets/brand/khas-lifestyle-logo.png' ); ?>" alt="<?php esc_attr_e( 'خاص', 'khas' ); ?>" width="44" height="44" />
				</a>
				<p><?php esc_html_e( 'مجله‌ای برای انتخاب‌های بهتر و ساختن یک زندگی خاص', 'khas' ); ?></p>
				<div class="socials">
					<a href="<?php echo esc_url( khas_social_url( 'instagram' ) ); ?>" target="_blank" rel="noreferrer" title="اینستاگرام" aria-label="Instagram">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
					</a>
					<a href="<?php echo esc_url( khas_social_url( 'telegram' ) ); ?>" target="_blank" rel="noreferrer" title="تلگرام" aria-label="Telegram">
						<svg viewBox="0 0 24 24" fill="currentColor"><path d="M21.9 4.3 18.6 20c-.2 1.1-.9 1.3-1.8.8l-5-3.7-2.4 2.3c-.3.3-.5.5-1 .5l.4-5 9.1-8.2c.4-.4-.1-.6-.6-.2L6 13.3l-4.8-1.5c-1-.3-1-1 .2-1.5l18.9-7.3c.9-.3 1.7.2 1.6 1.3z"/></svg>
					</a>
					<a href="<?php echo esc_url( khas_social_url( 'twitter' ) ); ?>" target="_blank" rel="noreferrer" title="توییتر" aria-label="Twitter">
						<svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 5.8c-.7.3-1.5.6-2.3.7.8-.5 1.5-1.3 1.8-2.3-.8.5-1.7.8-2.6 1a4 4 0 0 0-6.8 3.6A11.3 11.3 0 0 1 3.9 4.6a4 4 0 0 0 1.2 5.3c-.6 0-1.2-.2-1.8-.5v.1a4 4 0 0 0 3.2 3.9c-.6.2-1.2.2-1.8.1a4 4 0 0 0 3.7 2.8A8 8 0 0 1 2 18a11.3 11.3 0 0 0 6.1 1.8c7.3 0 11.4-6.1 11.4-11.4v-.5c.8-.6 1.5-1.3 2-2.1z"/></svg>
					</a>
					<a href="<?php echo esc_url( khas_social_url( 'pinterest' ) ); ?>" target="_blank" rel="noreferrer" title="پینترست" aria-label="Pinterest">
						<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-3.6 19.3c-.1-.8-.2-2 0-2.9l1.2-5s-.3-.6-.3-1.5c0-1.4.8-2.4 1.8-2.4.9 0 1.3.6 1.3 1.4 0 .9-.5 2.2-.8 3.4-.2 1 .5 1.8 1.5 1.8 1.8 0 3.1-1.9 3.1-4.6 0-2.4-1.7-4.1-4.2-4.1-2.9 0-4.5 2.1-4.5 4.3 0 .9.3 1.8.8 2.3.1.1.1.2.1.3l-.3 1.2c0 .2-.2.3-.4.2-1.3-.6-2.1-2.5-2.1-4 0-3.3 2.4-6.3 6.9-6.3 3.6 0 6.4 2.6 6.4 6 0 3.6-2.3 6.5-5.4 6.5-1.1 0-2.1-.6-2.4-1.2l-.7 2.5c-.2 1-.9 2.2-1.4 2.9A10 10 0 1 0 12 2z"/></svg>
					</a>
				</div>
			</div>

			<div>
				<h4><?php esc_html_e( 'دسترسی سریع', 'khas' ); ?></h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'درباره ما', 'khas' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'تماس با ما', 'khas' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'همکاری با ما', 'khas' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'سوالات متداول', 'khas' ); ?></a></li>
				</ul>
			</div>

			<div>
				<h4><?php esc_html_e( 'دسته‌ها', 'khas' ); ?></h4>
				<ul>
					<?php if ( $khas_footer_cats ) : foreach ( $khas_footer_cats as $fc ) : ?>
						<li><a href="<?php echo esc_url( get_category_link( $fc->term_id ) ); ?>"><?php echo esc_html( $fc->name ); ?></a></li>
					<?php endforeach; endif; ?>
				</ul>
			</div>

			<div>
				<h4><?php esc_html_e( 'اطلاعات', 'khas' ); ?></h4>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'حریم خصوصی', 'khas' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'خبرنامه', 'khas' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'تبلیغات', 'khas' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'قوانین و مقررات', 'khas' ); ?></a></li>
				</ul>
			</div>

			<div>
				<h4><?php esc_html_e( 'عضویت ویژه', 'khas' ); ?></h4>
				<ul>
					<li><a href="<?php echo esc_url( khas_membership_url() ); ?>"><?php esc_html_e( 'مزایا', 'khas' ); ?></a></li>
					<li><a href="<?php echo esc_url( khas_membership_url() ); ?>"><?php esc_html_e( 'تعرفه‌ها', 'khas' ); ?></a></li>
					<li><a href="<?php echo esc_url( khas_membership_url() ); ?>"><?php esc_html_e( 'عضویت و پرداخت', 'khas' ); ?></a></li>
				</ul>
			</div>

		</div>

		<div class="khas-footer__bottom">
			<p><?php printf( esc_html__( 'تمامی حقوق محفوظ است برای %s · ۱۴۰۳', 'khas' ), esc_html( get_bloginfo( 'name' ) ) ); ?></p>
			<p><?php esc_html_e( 'طراحی و توسعه توسط تیم خاص', 'khas' ); ?></p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
