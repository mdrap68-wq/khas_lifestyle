<?php
/**
 * Payment result / receipt page.
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

$success = ( 'success' === $payment->status );

get_header();
?>

<section class="khas-section" style="padding-top:120px;">
	<div class="khas-container" style="max-width:480px;">
		<div class="khas-receipt <?php echo $success ? 'is-success' : 'is-failed'; ?>">
			<div class="khas-receipt__status">
				<div class="icon"><?php echo $success ? '✓' : '✕'; ?></div>
				<h1><?php echo $success ? esc_html__( 'پرداخت با موفقیت انجام شد', 'khas' ) : esc_html__( 'پرداخت ناموفق بود', 'khas' ); ?></h1>
				<p>
					<?php
					echo $success
						? esc_html__( 'اشتراک ویژه شما فعال شد. به جمع خاص خوش آمدید! ✨', 'khas' )
						: esc_html__( 'تراکنش لغو شد یا با خطا مواجه شد. مبلغی از حساب شما کسر نشده است.', 'khas' );
					?>
				</p>
			</div>

			<div class="khas-receipt__rows">
				<div class="row"><span><?php esc_html_e( 'پلن انتخابی', 'khas' ); ?></span><strong><?php echo esc_html( $payment->plan_title ); ?></strong></div>
				<div class="row"><span><?php esc_html_e( 'مبلغ', 'khas' ); ?></span><strong><?php echo esc_html( khas_fa_money( $payment->amount ) ); ?> <?php esc_html_e( 'تومان', 'khas' ); ?></strong></div>
				<div class="row"><span><?php esc_html_e( 'نام', 'khas' ); ?></span><strong><?php echo esc_html( $payment->full_name ); ?></strong></div>
				<?php if ( $success && $payment->ref_id ) : ?>
					<div class="row"><span><?php esc_html_e( 'شماره پیگیری', 'khas' ); ?></span><strong dir="ltr"><?php echo esc_html( khas_fa_num( (int) $payment->ref_id ) ); ?></strong></div>
				<?php endif; ?>
				<?php if ( $success && $payment->card_mask ) : ?>
					<div class="row"><span><?php esc_html_e( 'کارت پرداخت', 'khas' ); ?></span><strong dir="ltr"><?php echo esc_html( $payment->card_mask ); ?></strong></div>
				<?php endif; ?>
				<div class="row">
					<span><?php esc_html_e( 'وضعیت', 'khas' ); ?></span>
					<strong class="<?php echo $success ? 'ok' : 'bad'; ?>">
						<?php echo $success ? esc_html__( 'موفق', 'khas' ) : esc_html__( 'ناموفق', 'khas' ); ?>
					</strong>
				</div>
			</div>

			<div class="khas-receipt__actions">
				<?php if ( $success ) : ?>
					<a class="khas-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'ورود به مجله خاص', 'khas' ); ?></a>
				<?php else : ?>
					<a class="khas-btn" href="<?php echo esc_url( khas_membership_url() ); ?>"><?php esc_html_e( 'تلاش مجدد', 'khas' ); ?></a>
				<?php endif; ?>
				<a class="link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'بازگشت به صفحه اصلی', 'khas' ); ?></a>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
