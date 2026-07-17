<?php
/**
 * KHAS | خاص — theme functions
 *
 * @package KHAS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'KHAS_VERSION', '2.2.0' );

// Demo content installer (admin-only).
if ( is_admin() ) {
	require_once get_template_directory() . '/inc/demo.php';
}

// Membership payment flow (front-end + AJAX).
require_once get_template_directory() . '/inc/payments.php';

/* -------------------------------------------------------------------------
 * Customizer: social links (Instagram, Telegram, Twitter, Pinterest).
 * ---------------------------------------------------------------------- */
function khas_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'khas_social',
		array(
			'title'    => __( 'شبکه‌های اجتماعی', 'khas' ),
			'priority' => 30,
		)
	);
	$socials = array(
		'instagram' => __( 'لینک اینستاگرام', 'khas' ),
		'telegram'  => __( 'لینک تلگرام', 'khas' ),
		'twitter'   => __( 'لینک توییتر', 'khas' ),
		'pinterest' => __( 'لینک پینترست', 'khas' ),
	);
	foreach ( $socials as $key => $label ) {
		$wp_customize->add_setting(
			'khas_' . $key . '_url',
			array(
				'default'           => 'instagram' === $key ? 'https://instagram.com' : '#',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			'khas_' . $key . '_url',
			array(
				'label'   => $label,
				'section' => 'khas_social',
				'type'    => 'url',
			)
		);
	}
}
add_action( 'customize_register', 'khas_customize_register' );

/**
 * Helper: return social URL from theme_mod.
 */
function khas_social_url( $key ) {
	$defaults = array(
		'instagram' => 'https://instagram.com',
		'telegram'  => '#',
		'twitter'   => '#',
		'pinterest' => '#',
	);
	return get_theme_mod( 'khas_' . $key . '_url', $defaults[ $key ] ?? '#' );
}

/* -------------------------------------------------------------------------
 * Hide the default "Uncategorized" category everywhere on the front-end.
 * ---------------------------------------------------------------------- */
add_filter(
	'get_terms_args',
	function ( $args, $taxonomies ) {
		if ( is_admin() ) {
			return $args;
		}
		if ( ! in_array( 'category', (array) $taxonomies, true ) ) {
			return $args;
		}
		if ( ! empty( $args['khas_allow_default'] ) ) {
			return $args;
		}
		$default = (int) get_option( 'default_category' );
		if ( $default > 0 ) {
			$existing        = isset( $args['exclude'] ) ? (array) $args['exclude'] : array();
			$args['exclude'] = array_values( array_unique( array_merge( $existing, array( $default ) ) ) );
		}
		return $args;
	},
	10,
	2
);

/* -------------------------------------------------------------------------
 * Theme setup
 * ---------------------------------------------------------------------- */
function khas_setup() {
	load_theme_textdomain( 'khas', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 180,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Featured image sizes used by the theme.
	add_image_size( 'khas-hero', 1400, 900, true );
	add_image_size( 'khas-card', 800, 520, true );
	add_image_size( 'khas-square', 500, 500, true );
	// Small square thumbnail used by the "آخرین مقالات" horizontal card —
	// cropped 1:1 at the source so it never needs a second (double) crop
	// via CSS object-fit, which was distorting/over-cropping the image.
	add_image_size( 'khas-thumb-sm', 168, 168, true );

	register_nav_menus(
		array(
			'primary' => __( 'منوی اصلی', 'khas' ),
			'footer'  => __( 'منوی فوتر', 'khas' ),
		)
	);
}
add_action( 'after_setup_theme', 'khas_setup' );

function khas_content_width() {
	$GLOBALS['content_width'] = 760;
}
add_action( 'after_setup_theme', 'khas_content_width', 0 );

/* -------------------------------------------------------------------------
 * Assets
 * ---------------------------------------------------------------------- */
function khas_assets() {
	// Vazirmatn Persian font.
	wp_enqueue_style(
		'khas-vazirmatn',
		'https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'khas-style', get_stylesheet_uri(), array( 'khas-vazirmatn' ), KHAS_VERSION );

	wp_enqueue_script( 'khas-main', get_template_directory_uri() . '/assets/main.js', array(), KHAS_VERSION, true );
	wp_localize_script(
		'khas-main',
		'KHAS',
		array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'khas_subscribe' ),
		)
	);

	// Reading progress bar (only on singular posts) + fade-in on scroll (everywhere).
	wp_enqueue_script( 'khas-reading', get_template_directory_uri() . '/assets/reading-progress.js', array(), KHAS_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'khas_assets' );

/* -------------------------------------------------------------------------
 * Premium comment form: Persian labels, cleaner fields, drop the default
 * "Logged in as..." line in favor of a refined author chip.
 * ---------------------------------------------------------------------- */
function khas_comment_form_args( $args, $post_id ) {
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = $req ? " aria-required='true'" : '';

	$args['title_reply']          = __( 'دیدگاه خود را بنویسید', 'khas' );
	$args['title_reply_to']       = __( 'پاسخ به %s', 'khas' );
	$args['title_reply_before']   = '<h3 class="khas-comment-form__title">';
	$args['title_reply_after']    = '</h3>';
	$args['cancel_reply_before']  = '<span class="khas-comment-form__cancel">';
	$args['cancel_reply_after']   = '</span>';
	$args['cancel_reply_link']    = __( 'لغو پاسخ', 'khas' );
	$args['label_submit']         = __( 'ارسال دیدگاه', 'khas' );
	$args['submit_button']        = '<button type="submit" class="khas-btn khas-comment-form__submit" id="%1$s">%4$s</button>';
	$args['comment_notes_before'] = '';
	$args['comment_notes_after']  = '<p class="khas-comment-form__note">' . __( 'ایمیل شما منتشر نخواهد شد. بخش‌های ضروری با * مشخص شده‌اند.', 'khas' ) . '</p>';
	$args['logged_in_as']         = '';
	$args['must_log_in']          = '<p class="khas-comment-form__must-login">' . sprintf( __( 'برای نوشتن دیدگاه باید <a href="%s">وارد شوید</a>.', 'khas' ), esc_url( wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) ) . '</p>';

	$args['fields'] = array(
		'author' => sprintf(
			'<div class="khas-comment-field"><label for="author">%1$s%2$s</label><input id="author" name="author" type="text" value="%3$s" size="30" maxlength="245"%4$s placeholder="%5$s" /></div>',
			__( 'نام', 'khas' ),
			( $req ? ' <span class="req">*</span>' : '' ),
			esc_attr( $commenter['comment_author'] ),
			$aria_req,
			esc_attr__( 'نام شما', 'khas' )
		),
		'email'  => sprintf(
			'<div class="khas-comment-field"><label for="email">%1$s%2$s</label><input id="email" name="email" type="email" value="%3$s" size="30" maxlength="100" aria-describedby="email-notes"%4$s placeholder="%5$s" /></div>',
			__( 'ایمیل', 'khas' ),
			( $req ? ' <span class="req">*</span>' : '' ),
			esc_attr( $commenter['comment_author_email'] ),
			$aria_req,
			esc_attr__( 'ایمیل شما (منتشر نمی‌شود)', 'khas' )
		),
		'url'    => sprintf(
			'<div class="khas-comment-field"><label for="url">%1$s</label><input id="url" name="url" type="url" value="%2$s" size="30" maxlength="200" placeholder="%3$s" /></div>',
			__( 'وب‌سایت', 'khas' ),
			esc_attr( $commenter['comment_author_url'] ),
			esc_attr__( 'اختیاری', 'khas' )
		),
	);

	$args['comment_field'] = sprintf(
		'<div class="khas-comment-field khas-comment-field--full"><label for="comment">%1$s <span class="req">*</span></label><textarea id="comment" name="comment" cols="45" rows="6" maxlength="65525" aria-required="true" placeholder="%2$s"></textarea></div>',
		__( 'دیدگاه شما', 'khas' ),
		esc_attr__( 'دیدگاه خود را اینجا بنویسید…', 'khas' )
	);

	return $args;
}
add_filter( 'comment_form_defaults', 'khas_comment_form_args', 10, 2 );
add_filter( 'comment_form_default_fields', function ( $fields ) { return $fields; } );

/* -------------------------------------------------------------------------
 * Add a first-letter class to the first paragraph of the post content
 * (only on singular posts) so CSS can render a drop cap.
 * ---------------------------------------------------------------------- */
function khas_add_dropcap_class( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}
	// Only add once per page.
	static $added = false;
	if ( $added ) {
		return $content;
	}
	$added = true;

	// Wrap first <p> tag's text with a drop-cap span on the first letter.
	return preg_replace_callback(
		'/<p([^>]*)>([\s\S]*?)<\/p>/i',
		function ( $m ) {
			$attrs = $m[1];
			$body  = $m[2];
			if ( strpos( $attrs, 'class="' ) !== false ) {
				$new_attrs = preg_replace( '/class="([^"]*)"/', 'class="$1 khas-dropcap-para"', $attrs );
			} else {
				$new_attrs = $attrs . ' class="khas-dropcap-para"';
			}
			return '<p' . $new_attrs . '>' . $body . '</p>';
		},
		$content,
		1
	);
}
add_filter( 'the_content', 'khas_add_dropcap_class' );

/* -------------------------------------------------------------------------
 * Widgets / sidebars
 * ---------------------------------------------------------------------- */
function khas_widgets() {
	register_sidebar(
		array(
			'name'          => __( 'ستون کناری', 'khas' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'ویجت‌های ستون کناری', 'khas' ),
			'before_widget' => '<section id="%1$s" class="khas-widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h4 class="khas-widget__title">',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'khas_widgets' );

/* -------------------------------------------------------------------------
 * Helpers
 * ---------------------------------------------------------------------- */

/**
 * Reading time in minutes (based on ~200 words / min, works fine for Persian).
 */
function khas_reading_time( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = max( 1, count( preg_split( '/\s+/u', wp_strip_all_tags( $content ) ) ) );
	$minutes = max( 1, (int) ceil( $words / 200 ) );
	return $minutes;
}

/**
 * Convert Latin digits to Persian digits.
 */
function khas_fa_num( $number ) {
	$latin   = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
	$persian = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
	return str_replace( $latin, $persian, (string) $number );
}

/**
 * Emoji icon for a category. Editable per-term via term meta "khas_icon",
 * falls back to a slug map and finally to a diamond.
 */
/**
 * Outline SVG icon for a category (matches the reference design's line-art style).
 */
function khas_category_svg( $term ) {
	$slug = is_object( $term ) ? $term->slug : (string) $term;
	$svg  = array(
		'wellness'  => '<path d="M12 21c0-4 2-7 5-8-3-1-5-4-5-8 0 4-2 7-5 8 3 1 5 4 5 8z"/><path d="M5 21h14"/>',
		'health'    => '<path d="M12 21c0-4 2-7 5-8-3-1-5-4-5-8 0 4-2 7-5 8 3 1 5 4 5 8z"/><path d="M5 21h14"/>',
		'home'      => '<path d="M3 11.5 12 4l9 7.5"/><path d="M5 10v10h14V10"/><path d="M12 13l1.6 1.2L13 16l-1.8-.2L10.4 17 10 15.2 8.4 14.2 10 13.4 10.6 11.6 12 13z"/>',
		'family'    => '<path d="M3 11.5 12 4l9 7.5"/><path d="M5 10v10h14V10"/>',
		'food'      => '<path d="M7 3v8"/><path d="M5 3v5a2 2 0 0 0 4 0V3"/><path d="M7 11v10"/><path d="M17 3c-1.5 0-3 2-3 5s1 4 3 4v9"/>',
		'kitchen'   => '<path d="M7 3v8"/><path d="M5 3v5a2 2 0 0 0 4 0V3"/><path d="M7 11v10"/><path d="M17 3c-1.5 0-3 2-3 5s1 4 3 4v9"/>',
		'tech'      => '<rect x="3" y="4" width="18" height="12" rx="1.5"/><path d="M8 20h8"/><path d="M12 16v4"/>',
		'technology'=> '<rect x="3" y="4" width="18" height="12" rx="1.5"/><path d="M8 20h8"/><path d="M12 16v4"/>',
		'car'       => '<path d="M5 17h14"/><path d="M6 17v2"/><path d="M18 17v2"/><path d="M4 17l1.5-5h13L20 17"/><path d="M4 17h16v-3H4z"/><circle cx="8" cy="14" r="1"/><circle cx="16" cy="14" r="1"/>',
		'auto'      => '<path d="M5 17h14"/><path d="M6 17v2"/><path d="M18 17v2"/><path d="M4 17l1.5-5h13L20 17"/><path d="M4 17h16v-3H4z"/>',
		'finance'   => '<path d="M3 7l9 4 9-4"/><path d="M3 7v10l9 4 9-4V7"/><path d="M12 11v10"/>',
		'business'  => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>',
		'travel'    => '<path d="M21 16v-2l-8-5V3.5a1.5 1.5 0 0 0-3 0V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5z"/>',
		'culture'   => '<path d="M4 5h6v6a3 3 0 0 1-6 0z"/><path d="M20 5h-6v6a3 3 0 0 0 6 0z"/><path d="M7 11v3"/><path d="M17 11v3"/><path d="M7 14a3 3 0 0 0 3 3"/><path d="M17 14a3 3 0 0 1-3 3"/>',
		'art'       => '<path d="M4 5h6v6a3 3 0 0 1-6 0z"/><path d="M20 5h-6v6a3 3 0 0 0 6 0z"/><path d="M7 11v3"/><path d="M17 11v3"/>',
		'pet'       => '<circle cx="6" cy="10" r="2"/><circle cx="10" cy="6" r="2"/><circle cx="14" cy="6" r="2"/><circle cx="18" cy="10" r="2"/><path d="M8 16c0-2 2-4 4-4s4 2 4 4-2 3-4 3-4-1-4-3z"/>',
		'pets'      => '<circle cx="6" cy="10" r="2"/><circle cx="10" cy="6" r="2"/><circle cx="14" cy="6" r="2"/><circle cx="18" cy="10" r="2"/><path d="M8 16c0-2 2-4 4-4s4 2 4 4-2 3-4 3-4-1-4-3z"/>',
		'fashion'   => '<path d="M12 3l3 3-2 2 4 4v9H7v-9l4-4-2-2z"/>',
		'beauty'    => '<circle cx="12" cy="9" r="5"/><path d="M9 14l-1 7 4-2 4 2-1-7"/>',
	);
	$paths = isset( $svg[ $slug ] ) ? $svg[ $slug ] : '<circle cx="12" cy="12" r="8"/><path d="M12 8v8M8 12h8"/>';
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $paths . '</svg>';
}

function khas_category_icon( $term ) {
	$map = array(
		'fashion'   => '👗',
		'beauty'    => '💄',
		'home'      => '🛋️',
		'decor'     => '🛋️',
		'food'      => '🍲',
		'recipe'    => '🍲',
		'culture'   => '🎭',
		'art'       => '🎭',
		'tech'      => '💻',
		'technology'=> '💻',
		'travel'    => '✈️',
		'health'    => '🧘',
		'wellness'  => '🧘',
		'finance'   => '💼',
		'business'  => '💼',
		'car'       => '🚗',
		'auto'      => '🚗',
		'pet'       => '🐾',
		'uncategorized' => '📁',
	);

	$icon = get_term_meta( $term->term_id, 'khas_icon', true );
	if ( $icon ) {
		return $icon;
	}
	if ( isset( $map[ $term->slug ] ) ) {
		return $map[ $term->slug ];
	}
	return '✦';
}

/**
 * Term icon field in the category edit screen.
 */
function khas_add_term_icon_field( $term ) {
	$value = get_term_meta( $term->term_id, 'khas_icon', true );
	?>
	<tr class="form-field">
		<th scope="row"><label for="khas_icon"><?php esc_html_e( 'آیکون (ایموجی)', 'khas' ); ?></label></th>
		<td>
			<input name="khas_icon" id="khas_icon" type="text" value="<?php echo esc_attr( $value ); ?>" placeholder="✦" />
			<p class="description"><?php esc_html_e( 'یک ایموجی برای نمایش در دایره‌های دسته‌بندی وارد کنید.', 'khas' ); ?></p>
		</td>
	</tr>
	<?php
}
add_action( 'category_edit_form_fields', 'khas_add_term_icon_field' );

function khas_save_term_icon( $term_id ) {
	if ( isset( $_POST['khas_icon'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		update_term_meta( $term_id, 'khas_icon', sanitize_text_field( wp_unslash( $_POST['khas_icon'] ) ) );
	}
}
add_action( 'edited_category', 'khas_save_term_icon' );
add_action( 'create_category', 'khas_save_term_icon' );

/**
 * Excerpt tweaks.
 */
function khas_excerpt_length() {
	return 22;
}
add_filter( 'excerpt_length', 'khas_excerpt_length' );

function khas_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'khas_excerpt_more' );

/**
 * Reusable card renderer.
 *
 * @param string $variant card | latest | feature-big | feature-side
 */
function khas_render_card( $variant = 'card' ) {
	$cats     = get_the_category();
	$cat      = ! empty( $cats ) ? $cats[0] : null;
	$cat_name = $cat ? $cat->name : '';
	$thumb    = get_the_post_thumbnail_url( get_the_ID(), 'khas-card' );
	if ( ! $thumb ) {
		$thumb = 'https://images.pexels.com/photos/20285350/pexels-photo-20285350.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=520&w=800';
	}
	$rt = khas_fa_num( khas_reading_time() );

	$author_name = get_the_author();
	$author_ini  = function_exists( 'mb_substr' ) ? mb_substr( $author_name, 0, 1 ) : substr( $author_name, 0, 3 );
	$author_id   = get_the_author_meta( 'ID' );
	$avatar_html = $author_id ? get_avatar( $author_id, 60, '', $author_name, array( 'class' => 'ava-img' ) ) : '';
	if ( $avatar_html ) {
		$avatar_html = wp_kses_post( $avatar_html );
	}

	if ( 'feature-big' === $variant ) {
		?>
		<a class="khas-feature-big" href="<?php the_permalink(); ?>">
			<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
			<span class="mask"></span>
			<div class="body">
				<?php if ( $cat_name ) : ?><span class="khas-badge"><?php echo esc_html( $cat_name ); ?></span><?php endif; ?>
				<h3><?php the_title(); ?></h3>
				<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20, '…' ) ); ?></p>
				<div class="foot">
					<span class="author"><span class="ava"><?php echo $avatar_html; ?><?php echo esc_html( $author_ini ); ?></span><?php echo esc_html( $author_name ); ?></span>
					<span class="sep"></span>
					<span><?php echo esc_html( $rt ); ?> دقیقه مطالعه</span>
				</div>
			</div>
		</a>
		<?php
		return;
	}

	if ( 'feature-side' === $variant ) {
		?>
		<a class="khas-feature-side" href="<?php the_permalink(); ?>">
			<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
			<span class="mask"></span>
			<div class="body">
				<?php if ( $cat_name ) : ?><span class="khas-badge"><?php echo esc_html( $cat_name ); ?></span><?php endif; ?>
				<h3><?php the_title(); ?></h3>
				<div class="foot">
					<span class="author"><span class="ava"><?php echo $avatar_html; ?><?php echo esc_html( $author_ini ); ?></span><?php echo esc_html( $author_name ); ?></span>
					<span class="sep"></span>
					<span><?php echo esc_html( $rt ); ?> دقیقه</span>
				</div>
			</div>
		</a>
		<?php
		return;
	}

	if ( 'latest' === $variant ) {
		// Use a source image already cropped 1:1 so the square display box
		// never needs a second CSS-level crop (which was cutting off the
		// subject of the photo and looking distorted/"messed up").
		$thumb_sq = get_the_post_thumbnail_url( get_the_ID(), 'khas-thumb-sm' );
		if ( ! $thumb_sq ) {
			$thumb_sq = $thumb;
		}
		?>
		<a class="khas-latest-card" href="<?php the_permalink(); ?>">
			<?php if ( $cat_name ) : ?><span class="cat"><?php echo esc_html( $cat_name ); ?></span><?php endif; ?>
			<div class="row">
				<div class="text">
					<h3><?php the_title(); ?></h3>
				</div>
				<div class="thumb"><img src="<?php echo esc_url( $thumb_sq ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" width="84" height="84" /></div>
			</div>
			<div class="rt">
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7.5v4.8l3 1.9"/></svg>
				<?php echo esc_html( $rt ); ?> دقیقه مطالعه
			</div>
		</a>
		<?php
		return;
	}

	// Default full card.
	?>
	<a class="khas-card" href="<?php the_permalink(); ?>">
		<div class="thumb"><img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" /></div>
		<div class="body">
			<?php if ( $cat_name ) : ?><span class="khas-badge"><?php echo esc_html( $cat_name ); ?></span><?php endif; ?>
			<h3><?php the_title(); ?></h3>
			<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18, '…' ) ); ?></p>
			<div class="foot">
				<div class="khas-meta">
					<span>
						<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7.5v4.8l3 1.9"/></svg>
						<?php echo esc_html( $rt ); ?> دقیقه مطالعه
					</span>
					<span class="sep"></span>
					<span><?php echo esc_html( get_the_author() ); ?></span>
				</div>
			</div>
		</div>
	</a>
	<?php
}

/* -------------------------------------------------------------------------
 * Newsletter — custom table + AJAX
 * ---------------------------------------------------------------------- */
function khas_create_subscribers_table() {
	global $wpdb;
	$table           = $wpdb->prefix . 'khas_subscribers';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$table} (
		id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		email VARCHAR(320) NOT NULL,
		created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		UNIQUE KEY email (email)
	) {$charset_collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}
add_action( 'after_switch_theme', 'khas_create_subscribers_table' );

function khas_handle_subscribe() {
	check_ajax_referer( 'khas_subscribe', 'nonce' );

	$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => 'لطفاً یک ایمیل معتبر وارد کنید.' ) );
	}

	global $wpdb;
	$table = $wpdb->prefix . 'khas_subscribers';

	$exists = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$table} WHERE email = %s", $email ) ); // phpcs:ignore

	if ( $exists ) {
		wp_send_json_success( array( 'message' => 'شما قبلاً عضو خبرنامه خاص شده‌اید. 🌿' ) );
	}

	$wpdb->insert( // phpcs:ignore
		$table,
		array(
			'email'      => $email,
			'created_at' => current_time( 'mysql' ),
		),
		array( '%s', '%s' )
	);

	wp_send_json_success( array( 'message' => 'عضویت شما با موفقیت ثبت شد. به جمع خاص خوش آمدید! ✨' ) );
}
add_action( 'wp_ajax_khas_subscribe', 'khas_handle_subscribe' );
add_action( 'wp_ajax_nopriv_khas_subscribe', 'khas_handle_subscribe' );

/**
 * Admin page to view subscribers.
 */
function khas_subscribers_menu() {
	add_menu_page(
		__( 'مشترکین خبرنامه', 'khas' ),
		__( 'خبرنامه خاص', 'khas' ),
		'manage_options',
		'khas-subscribers',
		'khas_subscribers_page',
		'dashicons-email-alt',
		30
	);
}
add_action( 'admin_menu', 'khas_subscribers_menu' );

function khas_subscribers_page() {
	global $wpdb;
	$table = $wpdb->prefix . 'khas_subscribers';
	$rows  = $wpdb->get_results( "SELECT * FROM {$table} ORDER BY created_at DESC LIMIT 500" ); // phpcs:ignore
	echo '<div class="wrap"><h1>مشترکین خبرنامه خاص</h1>';
	echo '<table class="wp-list-table widefat fixed striped"><thead><tr><th>#</th><th>ایمیل</th><th>تاریخ</th></tr></thead><tbody>';
	if ( $rows ) {
		foreach ( $rows as $r ) {
			echo '<tr><td>' . esc_html( $r->id ) . '</td><td>' . esc_html( $r->email ) . '</td><td>' . esc_html( $r->created_at ) . '</td></tr>';
		}
	} else {
		echo '<tr><td colspan="3">هنوز مشترکی ثبت نشده است.</td></tr>';
	}
	echo '</tbody></table></div>';
}
