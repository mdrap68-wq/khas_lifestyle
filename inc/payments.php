<?php
/**
 * Membership payment flow for KHAS theme.
 *
 * Creates a payments table, membership pricing page, bank-gateway
 * simulator, and result/receipt page. Links "عضویت ویژه" to this flow.
 *
 * @package KHAS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Plans
 * ---------------------------------------------------------------------- */
function khas_get_plans() {
	return array(
		'monthly'   => array(
			'slug'            => 'monthly',
			'title'           => 'اشتراک ماهانه',
			'period'          => 'هر ماه',
			'amount'          => 99000,
			'original_amount' => 0,
			'badge'           => '',
			'highlight'       => false,
			'features'        => array(
				'دسترسی کامل به همه مقالات ویژه',
				'بدون تبلیغات',
				'خبرنامه اختصاصی اعضا',
			),
		),
		'quarterly' => array(
			'slug'            => 'quarterly',
			'title'           => 'اشتراک سه‌ماهه',
			'period'          => 'هر ۳ ماه',
			'amount'          => 249000,
			'original_amount' => 297000,
			'badge'           => 'پرطرفدار',
			'highlight'       => true,
			'features'        => array(
				'تمام مزایای پلن ماهانه',
				'۱۶٪ صرفه‌جویی نسبت به ماهانه',
				'دسترسی زودهنگام به مقالات جدید',
				'پشتیبانی اولویت‌دار',
			),
		),
		'yearly'    => array(
			'slug'            => 'yearly',
			'title'           => 'اشتراک سالانه',
			'period'          => 'هر سال',
			'amount'          => 790000,
			'original_amount' => 1188000,
			'badge'           => 'بهترین ارزش',
			'highlight'       => false,
			'features'        => array(
				'تمام مزایای پلن سه‌ماهه',
				'۳۳٪ صرفه‌جویی نسبت به ماهانه',
				'دریافت نسخه چاپی فصلی مجله',
				'دعوت به رویدادهای ویژه اعضا',
			),
		),
	);
}

function khas_get_plan( $slug ) {
	$plans = khas_get_plans();
	return isset( $plans[ $slug ] ) ? $plans[ $slug ] : null;
}

/* -------------------------------------------------------------------------
 * DB table
 * ---------------------------------------------------------------------- */
function khas_create_payments_table() {
	global $wpdb;
	$table           = $wpdb->prefix . 'khas_payments';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$table} (
		id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		token VARCHAR(64) NOT NULL,
		plan_slug VARCHAR(40) NOT NULL,
		plan_title VARCHAR(120) NOT NULL,
		amount INT NOT NULL,
		full_name VARCHAR(160) NOT NULL,
		email VARCHAR(320) NOT NULL,
		mobile VARCHAR(20) NOT NULL,
		status VARCHAR(20) NOT NULL DEFAULT 'pending',
		ref_id VARCHAR(40) DEFAULT NULL,
		card_mask VARCHAR(24) DEFAULT NULL,
		created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		paid_at DATETIME DEFAULT NULL,
		PRIMARY KEY (id),
		UNIQUE KEY token (token)
	) {$charset_collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}
add_action( 'after_switch_theme', 'khas_create_payments_table' );
// Also ensure table exists on every admin load (safe / idempotent).
add_action( 'admin_init', 'khas_create_payments_table' );

/* -------------------------------------------------------------------------
 * Rewrite rules for /membership, /payment/gateway/{token}, /payment/result/{token}
 * ---------------------------------------------------------------------- */
function khas_payment_rewrite_rules() {
	add_rewrite_rule( '^membership/?$', 'index.php?khas_pay=membership', 'top' );
	add_rewrite_rule( '^payment/gateway/([^/]+)/?$', 'index.php?khas_pay=gateway&khas_token=$matches[1]', 'top' );
	add_rewrite_rule( '^payment/result/([^/]+)/?$', 'index.php?khas_pay=result&khas_token=$matches[1]', 'top' );
}
add_action( 'init', 'khas_payment_rewrite_rules' );

function khas_payment_query_vars( $vars ) {
	$vars[] = 'khas_pay';
	$vars[] = 'khas_token';
	return $vars;
}
add_filter( 'query_vars', 'khas_payment_query_vars' );

function khas_payment_flush_rewrites() {
	khas_payment_rewrite_rules();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'khas_payment_flush_rewrites' );

// One-time flush when payments module is first loaded after theme update.
function khas_maybe_flush_payment_rewrites() {
	if ( get_option( 'khas_payment_rewrites_v1' ) ) {
		return;
	}
	khas_payment_rewrite_rules();
	flush_rewrite_rules( false );
	update_option( 'khas_payment_rewrites_v1', 1 );
}
add_action( 'init', 'khas_maybe_flush_payment_rewrites', 20 );

function khas_payment_template_include( $template ) {
	$mode = get_query_var( 'khas_pay' );
	if ( ! $mode ) {
		return $template;
	}

	$map = array(
		'membership' => 'membership.php',
		'gateway'    => 'payment-gateway.php',
		'result'     => 'payment-result.php',
	);

	if ( ! isset( $map[ $mode ] ) ) {
		return $template;
	}

	$file = get_template_directory() . '/' . $map[ $mode ];
	return file_exists( $file ) ? $file : $template;
}
add_filter( 'template_include', 'khas_payment_template_include' );

/* -------------------------------------------------------------------------
 * Membership page URL helper
 * ---------------------------------------------------------------------- */
function khas_membership_url() {
	return home_url( '/membership/' );
}

function khas_payment_gateway_url( $token ) {
	return home_url( '/payment/gateway/' . rawurlencode( $token ) . '/' );
}

function khas_payment_result_url( $token ) {
	return home_url( '/payment/result/' . rawurlencode( $token ) . '/' );
}

/* -------------------------------------------------------------------------
 * AJAX: initiate payment
 * ---------------------------------------------------------------------- */
function khas_handle_payment_initiate() {
	check_ajax_referer( 'khas_payment', 'nonce' );

	$plan_slug = isset( $_POST['plan_slug'] ) ? sanitize_key( wp_unslash( $_POST['plan_slug'] ) ) : '';
	$full_name = isset( $_POST['full_name'] ) ? sanitize_text_field( wp_unslash( $_POST['full_name'] ) ) : '';
	$email     = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$mobile    = isset( $_POST['mobile'] ) ? sanitize_text_field( wp_unslash( $_POST['mobile'] ) ) : '';

	$plan = khas_get_plan( $plan_slug );
	if ( ! $plan ) {
		wp_send_json_error( array( 'message' => 'پلن انتخاب‌شده معتبر نیست.' ) );
	}
	if ( mb_strlen( $full_name ) < 3 ) {
		wp_send_json_error( array( 'message' => 'لطفاً نام و نام خانوادگی خود را کامل وارد کنید.' ) );
	}
	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => 'ایمیل واردشده معتبر نیست.' ) );
	}
	if ( ! preg_match( '/^09\d{9}$/', $mobile ) ) {
		wp_send_json_error( array( 'message' => 'شماره موبایل باید با ۰۹ شروع شود و ۱۱ رقم باشد.' ) );
	}

	// Ensure table exists even if theme was activated before this module.
	khas_create_payments_table();

	$token = bin2hex( random_bytes( 16 ) );
	global $wpdb;
	$table = $wpdb->prefix . 'khas_payments';

	$inserted = $wpdb->insert( // phpcs:ignore
		$table,
		array(
			'token'      => $token,
			'plan_slug'  => $plan['slug'],
			'plan_title' => $plan['title'],
			'amount'     => (int) $plan['amount'],
			'full_name'  => $full_name,
			'email'      => $email,
			'mobile'     => $mobile,
			'status'     => 'pending',
			'created_at' => current_time( 'mysql' ),
		),
		array( '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s' )
	);

	if ( ! $inserted ) {
		wp_send_json_error( array( 'message' => 'خطا در ایجاد تراکنش. دوباره تلاش کنید.' ) );
	}

	wp_send_json_success(
		array(
			'token'        => $token,
			'redirect_url' => khas_payment_gateway_url( $token ),
		)
	);
}
add_action( 'wp_ajax_khas_payment_initiate', 'khas_handle_payment_initiate' );
add_action( 'wp_ajax_nopriv_khas_payment_initiate', 'khas_handle_payment_initiate' );

/* -------------------------------------------------------------------------
 * AJAX: verify / cancel payment
 * ---------------------------------------------------------------------- */
function khas_handle_payment_verify() {
	check_ajax_referer( 'khas_payment', 'nonce' );

	$token  = isset( $_POST['token'] ) ? sanitize_text_field( wp_unslash( $_POST['token'] ) ) : '';
	$action = isset( $_POST['action_type'] ) ? sanitize_key( wp_unslash( $_POST['action_type'] ) ) : 'pay';
	$last4  = isset( $_POST['card_last4'] ) ? preg_replace( '/\D/', '', wp_unslash( $_POST['card_last4'] ) ) : '0000';
	$last4  = substr( $last4, -4 );

	if ( ! $token ) {
		wp_send_json_error( array( 'message' => 'توکن تراکنش نامعتبر است.' ) );
	}

	global $wpdb;
	$table   = $wpdb->prefix . 'khas_payments';
	$payment = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE token = %s", $token ) ); // phpcs:ignore

	if ( ! $payment ) {
		wp_send_json_error( array( 'message' => 'تراکنش یافت نشد.' ) );
	}

	if ( 'pending' !== $payment->status ) {
		wp_send_json_success(
			array(
				'status' => $payment->status,
				'ref_id' => $payment->ref_id,
			)
		);
	}

	if ( 'cancel' === $action ) {
		$wpdb->update( // phpcs:ignore
			$table,
			array( 'status' => 'failed' ),
			array( 'token' => $token ),
			array( '%s' ),
			array( '%s' )
		);
		wp_send_json_success( array( 'status' => 'failed' ) );
	}

	$ref_id    = (string) wp_rand( 100000000, 999999999 );
	$card_mask = '۶۰۳۷-****-****-' . $last4;

	$wpdb->update( // phpcs:ignore
		$table,
		array(
			'status'    => 'success',
			'ref_id'    => $ref_id,
			'card_mask' => $card_mask,
			'paid_at'   => current_time( 'mysql' ),
		),
		array( 'token' => $token ),
		array( '%s', '%s', '%s', '%s' ),
		array( '%s' )
	);

	// Optional: also store email in newsletter subscribers if table exists.
	$sub_table = $wpdb->prefix . 'khas_subscribers';
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$exists = $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", $sub_table ) );
	if ( $exists ) {
		$wpdb->query( // phpcs:ignore
			$wpdb->prepare(
				"INSERT IGNORE INTO {$sub_table} (email, created_at) VALUES (%s, %s)",
				$payment->email,
				current_time( 'mysql' )
			)
		);
	}

	wp_send_json_success(
		array(
			'status' => 'success',
			'ref_id' => $ref_id,
		)
	);
}
add_action( 'wp_ajax_khas_payment_verify', 'khas_handle_payment_verify' );
add_action( 'wp_ajax_nopriv_khas_payment_verify', 'khas_handle_payment_verify' );

/* -------------------------------------------------------------------------
 * Helpers
 * ---------------------------------------------------------------------- */
function khas_get_payment_by_token( $token ) {
	global $wpdb;
	$table = $wpdb->prefix . 'khas_payments';
	return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE token = %s", $token ) ); // phpcs:ignore
}

function khas_fa_money( $amount ) {
	return khas_fa_num( (int) $amount );
}

/* -------------------------------------------------------------------------
 * Enqueue payment JS only on payment pages
 * ---------------------------------------------------------------------- */
function khas_payment_assets() {
	$mode = get_query_var( 'khas_pay' );
	if ( ! $mode ) {
		return;
	}

	wp_enqueue_script(
		'khas-payment',
		get_template_directory_uri() . '/assets/payment.js',
		array(),
		KHAS_VERSION,
		true
	);
	wp_localize_script(
		'khas-payment',
		'KHAS_PAY',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'khas_payment' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'khas_payment_assets' );
