<?php
/**
 * Membership / pricing page.
 *
 * @package KHAS
 */

get_header();
$plans = khas_get_plans();
?>

<section class="khas-section khas-section--cream" style="padding-top:120px;">
	<div class="khas-container" style="text-align:center;">
		<div class="khas-eyebrow" style="justify-content:center;margin-bottom:18px;"><?php esc_html_e( 'عضویت ویژه خاص', 'khas' ); ?></div>
		<h1 style="font-size:clamp(28px,4vw,42px);font-weight:900;color:var(--c-ink);letter-spacing:-0.03em;">
			<?php esc_html_e( 'به جمع اعضای ویژه بپیوندید', 'khas' ); ?>
		</h1>
		<p style="max-width:560px;margin:16px auto 0;color:var(--c-muted);font-size:15px;line-height:1.9;">
			<?php esc_html_e( 'با اشتراک ویژه خاص، به همه مقالات، راهنماها و محتوای انحصاری دسترسی کامل داشته باشید و از تجربه‌ای بدون تبلیغات لذت ببرید.', 'khas' ); ?>
		</p>
	</div>
</section>

<section class="khas-section" style="padding-top:40px;">
	<div class="khas-container">
		<div class="khas-plans-grid">
			<?php foreach ( $plans as $plan ) : ?>
				<div class="khas-plan-card<?php echo ! empty( $plan['highlight'] ) ? ' is-highlight' : ''; ?>">
					<?php if ( ! empty( $plan['badge'] ) ) : ?>
						<span class="khas-plan-card__badge"><?php echo esc_html( $plan['badge'] ); ?></span>
					<?php endif; ?>
					<h3><?php echo esc_html( $plan['title'] ); ?></h3>
					<p class="period"><?php echo esc_html( $plan['period'] ); ?></p>
					<div class="price">
						<span class="num"><?php echo esc_html( khas_fa_money( $plan['amount'] ) ); ?></span>
						<span class="unit"><?php esc_html_e( 'تومان', 'khas' ); ?></span>
					</div>
					<?php if ( ! empty( $plan['original_amount'] ) ) : ?>
						<div class="old-price"><?php echo esc_html( khas_fa_money( $plan['original_amount'] ) ); ?> <?php esc_html_e( 'تومان', 'khas' ); ?></div>
					<?php endif; ?>
					<ul>
						<?php foreach ( $plan['features'] as $f ) : ?>
							<li><span>✓</span><?php echo esc_html( $f ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a class="khas-btn<?php echo empty( $plan['highlight'] ) ? ' khas-btn--ghost' : ''; ?>" href="#khas-checkout" data-plan="<?php echo esc_attr( $plan['slug'] ); ?>">
						<?php esc_html_e( 'انتخاب این پلن', 'khas' ); ?>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="khas-section khas-section--warm" id="khas-checkout" style="padding-top:48px;">
	<div class="khas-container" style="max-width:680px;">
		<div style="text-align:center;margin-bottom:28px;">
			<div class="khas-eyebrow" style="justify-content:center;margin-bottom:12px;"><?php esc_html_e( 'تکمیل خرید', 'khas' ); ?></div>
			<h2 style="font-size:24px;font-weight:900;color:var(--c-ink);"><?php esc_html_e( 'اطلاعات خود را وارد کنید', 'khas' ); ?></h2>
		</div>

		<form class="khas-checkout-form" id="khas-checkout-form">
			<div class="khas-field">
				<label><?php esc_html_e( 'انتخاب پلن اشتراک', 'khas' ); ?></label>
				<div class="khas-plan-pills">
					<?php foreach ( $plans as $plan ) : ?>
						<label class="khas-plan-pill">
							<input type="radio" name="plan_slug" value="<?php echo esc_attr( $plan['slug'] ); ?>" <?php checked( $plan['slug'], 'quarterly' ); ?> />
							<span>
								<strong><?php echo esc_html( $plan['title'] ); ?></strong>
								<em><?php echo esc_html( khas_fa_money( $plan['amount'] ) ); ?> تومان</em>
							</span>
						</label>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="khas-field">
				<label for="khas-full-name"><?php esc_html_e( 'نام و نام خانوادگی', 'khas' ); ?></label>
				<input id="khas-full-name" name="full_name" type="text" required placeholder="<?php esc_attr_e( 'مثلاً: نرگس امیری', 'khas' ); ?>" />
			</div>

			<div class="khas-field-row">
				<div class="khas-field">
					<label for="khas-email"><?php esc_html_e( 'ایمیل', 'khas' ); ?></label>
					<input id="khas-email" name="email" type="email" required dir="ltr" placeholder="you@example.com" />
				</div>
				<div class="khas-field">
					<label for="khas-mobile"><?php esc_html_e( 'شماره موبایل', 'khas' ); ?></label>
					<input id="khas-mobile" name="mobile" type="text" required dir="ltr" placeholder="09xxxxxxxxx" />
				</div>
			</div>

			<p class="khas-checkout-error" id="khas-checkout-error" hidden></p>

			<div class="khas-checkout-summary">
				<div class="row">
					<span><?php esc_html_e( 'مبلغ قابل پرداخت', 'khas' ); ?></span>
					<strong id="khas-checkout-amount"><?php echo esc_html( khas_fa_money( 249000 ) ); ?> <?php esc_html_e( 'تومان', 'khas' ); ?></strong>
				</div>
				<button type="submit" class="khas-btn" id="khas-checkout-submit">
					<?php esc_html_e( 'پرداخت و فعال‌سازی اشتراک', 'khas' ); ?>
				</button>
				<p class="note"><?php esc_html_e( 'با کلیک روی دکمه، به درگاه امن بانکی منتقل می‌شوید.', 'khas' ); ?></p>
			</div>
		</form>

		<div class="khas-trust-row">
			<span>🔒 <?php esc_html_e( 'پرداخت امن بانکی', 'khas' ); ?></span>
			<span>↩️ <?php esc_html_e( 'تضمین بازگشت وجه', 'khas' ); ?></span>
			<span>🛡️ <?php esc_html_e( 'حفاظت از اطلاعات', 'khas' ); ?></span>
		</div>
	</div>
</section>

<?php
get_footer();
