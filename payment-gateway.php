<?php
/**
 * Bank gateway simulator.
 *
 * @package KHAS
 */

$token   = get_query_var( 'khas_token' );
$payment = $token ? khas_get_payment_by_token( $token ) : null;

if ( ! $payment ) {
	status_header( 404 );
	nocache_headers();
	include get_query_template( '404' );
	exit;
}

if ( 'pending' !== $payment->status ) {
	wp_safe_redirect( khas_payment_result_url( $payment->token ) );
	exit;
}

// Minimal chrome-less layout for gateway look.
?><!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title><?php esc_html_e( 'درگاه پرداخت امن', 'khas' ); ?></title>
	<?php wp_head(); ?>
	<style>
		body { background:#0d0f10 !important; }
		.khas-gateway-wrap { min-height:100vh; display:grid; place-items:center; padding:32px 16px; }
	</style>
</head>
<body <?php body_class( 'khas-gateway-body' ); ?>>
<div class="khas-gateway-wrap">
	<div class="khas-gateway" data-token="<?php echo esc_attr( $payment->token ); ?>" data-amount="<?php echo esc_attr( $payment->amount ); ?>">
		<div class="khas-gateway__head">
			<div class="brand">
				<span class="lock">🔒</span>
				<div>
					<strong><?php esc_html_e( 'درگاه پرداخت امن', 'khas' ); ?></strong>
					<small>shaparak · خاص پی</small>
				</div>
			</div>
			<div class="timer">
				<span><?php esc_html_e( 'زمان باقی‌مانده', 'khas' ); ?></span>
				<strong id="khas-gateway-timer" dir="ltr">10:00</strong>
			</div>
		</div>

		<form class="khas-gateway__form" id="khas-gateway-form">
			<div class="amount-box">
				<span><?php esc_html_e( 'مبلغ قابل پرداخت', 'khas' ); ?></span>
				<strong><?php echo esc_html( khas_fa_money( $payment->amount ) ); ?> <em><?php esc_html_e( 'تومان', 'khas' ); ?></em></strong>
				<small><?php echo esc_html( $payment->plan_title ); ?></small>
			</div>

			<label><?php esc_html_e( 'شماره کارت', 'khas' ); ?></label>
			<input id="khas-card" name="card" type="text" dir="ltr" inputmode="numeric" placeholder="•••• •••• •••• ••••" autocomplete="off" />

			<div class="row-2">
				<div>
					<label><?php esc_html_e( 'CVV2', 'khas' ); ?></label>
					<input id="khas-cvv2" name="cvv2" type="text" dir="ltr" inputmode="numeric" maxlength="4" placeholder="•••" autocomplete="off" />
				</div>
				<div>
					<label><?php esc_html_e( 'تاریخ انقضا', 'khas' ); ?></label>
					<div class="exp" dir="ltr">
						<input id="khas-exp-month" type="text" inputmode="numeric" maxlength="2" placeholder="ماه" autocomplete="off" />
						<input id="khas-exp-year" type="text" inputmode="numeric" maxlength="2" placeholder="سال" autocomplete="off" />
					</div>
				</div>
			</div>

			<label><?php esc_html_e( 'رمز دوم (رمز پویا)', 'khas' ); ?></label>
			<div class="pin-row">
				<input id="khas-pin" name="pin" type="password" dir="ltr" inputmode="numeric" placeholder="<?php esc_attr_e( 'رمز پویا', 'khas' ); ?>" autocomplete="off" />
				<button type="button" class="ghost" id="khas-otp-btn"><?php esc_html_e( 'دریافت رمز', 'khas' ); ?></button>
			</div>

			<p class="khas-gateway-error" id="khas-gateway-error" hidden></p>

			<button type="submit" class="pay" id="khas-pay-btn"><?php esc_html_e( 'پرداخت', 'khas' ); ?></button>
			<button type="button" class="cancel" id="khas-cancel-btn"><?php esc_html_e( 'انصراف از پرداخت', 'khas' ); ?></button>

			<p class="hint">
				<?php esc_html_e( 'این یک درگاه نمایشی برای تست است. اطلاعات کارت واقعی وارد نکنید. برای تست موفق، هر شماره کارت ۱۶ رقمی وارد کنید.', 'khas' ); ?>
			</p>
		</form>
	</div>
</div>
<?php wp_footer(); ?>
</body>
</html>
