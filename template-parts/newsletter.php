<?php
/**
 * Newsletter block — wide teal banner with side image.
 *
 * @package KHAS
 */
?>
<section class="khas-section" id="khas-newsletter" style="padding-top:0;">
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
