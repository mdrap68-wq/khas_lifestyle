<?php
/**
 * 404 template.
 *
 * @package KHAS
 */

get_header();
?>

<section class="khas-empty">
	<div class="big">۴۰۴</div>
	<h1><?php esc_html_e( 'صفحه‌ای که دنبالش بودید پیدا نشد', 'khas' ); ?></h1>
	<p><?php esc_html_e( 'شاید این مطلب حذف شده یا آدرس اشتباه است.', 'khas' ); ?></p>
	<a class="khas-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'بازگشت به خانه', 'khas' ); ?></a>
</section>

<?php
get_footer();
