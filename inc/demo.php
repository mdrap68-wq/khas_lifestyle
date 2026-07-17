<?php
/**
 * Demo content installer for KHAS theme.
 *
 * Creates categories with icons, sample posts with featured images,
 * a primary menu, a front page, and marks one post sticky — so the
 * site matches the reference design immediately after install.
 *
 * @package KHAS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin notice shown after theme activation.
 */
function khas_demo_activation_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( get_option( 'khas_demo_installed' ) ) {
		return;
	}
	if ( isset( $_GET['page'] ) && 'khas-demo' === $_GET['page'] ) { // phpcs:ignore
		return;
	}
	$url = admin_url( 'themes.php?page=khas-demo' );
	?>
	<div class="notice notice-info is-dismissible">
		<p>
			<strong>قالب خاص فعال شد 🎉</strong>
			برای اینکه سایت دقیقاً مثل طرح اصلی نمایش داده شود،
			<a href="<?php echo esc_url( $url ); ?>" style="font-weight:700;color:#2f9aa8;">محتوای دمو را نصب کنید</a>.
			(دسته‌بندی‌ها، مقالات نمونه با تصویر، منو و صفحه خانه ساخته می‌شود.)
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'khas_demo_activation_notice' );

/**
 * Register admin page.
 */
function khas_demo_menu() {
	add_theme_page(
		__( 'نصب محتوای دمو', 'khas' ),
		__( 'محتوای دمو', 'khas' ),
		'manage_options',
		'khas-demo',
		'khas_demo_page'
	);
}
add_action( 'admin_menu', 'khas_demo_menu' );

/**
 * Admin page UI.
 */
function khas_demo_page() {
	$installed = (bool) get_option( 'khas_demo_installed' );
	?>
	<div class="wrap" style="direction:rtl;text-align:right;">
		<h1>نصب محتوای دمو خاص</h1>
		<p>
			با نصب محتوای دمو، موارد زیر به‌صورت خودکار ساخته می‌شوند:
		</p>
		<ul style="list-style:disc;padding-right:24px;line-height:2.2;">
			<li>۸ دسته‌بندی با آیکون (مد و زیبایی، خانه و دکوراسیون، غذا و آشپزی، فرهنگ و هنر، تکنولوژی، سفر و تجربه، سلامت و تندرستی، مالی و کار)</li>
			<li>۴۸ مقاله نمونه با تصویر شاخص — دقیقاً ۶ مقاله برای هر دسته (از Pexels، تحت مجوز آزاد)</li>
			<li>یک مقاله sticky برای نمایش در هیرو</li>
			<li>برگه «خانه» و تنظیم آن به‌عنوان صفحه اصلی</li>
			<li>منوی اصلی با لینک دسته‌ها</li>
			<li>حذف پست پیش‌فرض Hello world</li>
		</ul>
		<p style="margin-top:20px;">
			<?php if ( $installed ) : ?>
				<span style="color:#2f9aa8;font-weight:700;">✓ محتوای دمو قبلاً نصب شده است.</span>
				<button type="button" class="button" id="khas-demo-reinstall" style="margin-right:12px;">نصب مجدد</button>
			<?php else : ?>
				<button type="button" class="button button-primary" id="khas-demo-install" style="background:#2f9aa8;border-color:#247e8b;font-size:14px;padding:8px 22px;">
					نصب محتوای دمو
				</button>
			<?php endif; ?>
		</p>
		<div id="khas-demo-progress" style="margin-top:20px;display:none;">
			<div style="background:#fff;border:1px solid #ccd0d4;border-radius:6px;padding:16px;max-width:600px;">
				<p style="margin:0 0 8px;"><strong id="khas-demo-status">در حال آماده‌سازی…</strong></p>
				<div style="background:#eef0f2;height:8px;border-radius:4px;overflow:hidden;">
					<div id="khas-demo-bar" style="background:#2f9aa8;height:100%;width:0%;transition:width .3s;"></div>
				</div>
				<p id="khas-demo-detail" style="margin:8px 0 0;font-size:12px;color:#555;"></p>
			</div>
		</div>
		<div id="khas-demo-result" style="margin-top:20px;"></div>
	</div>
	<script>
	(function () {
		var btn = document.getElementById('khas-demo-install') || document.getElementById('khas-demo-reinstall');
		var progress = document.getElementById('khas-demo-progress');
		var bar = document.getElementById('khas-demo-bar');
		var status = document.getElementById('khas-demo-status');
		var detail = document.getElementById('khas-demo-detail');
		var result = document.getElementById('khas-demo-result');
		if (!btn) return;
		btn.addEventListener('click', function () {
			if (!confirm('محتوای دمو نصب شود؟')) return;
			btn.disabled = true;
			progress.style.display = 'block';
			result.innerHTML = '';
			var data = new URLSearchParams();
			data.append('action', 'khas_install_demo');
			data.append('nonce', '<?php echo esc_js( wp_create_nonce( 'khas_demo' ) ); ?>');
			var xhr = new XMLHttpRequest();
			xhr.open('POST', ajaxurl, true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xhr.upload.addEventListener('progress', function (e) {
				if (e.lengthComputable) {
					var p = Math.round((e.loaded / e.total) * 100);
					bar.style.width = p + '%';
					detail.textContent = 'در حال ارسال درخواست… ' + p + '%';
				}
			});
			xhr.addEventListener('load', function () {
				bar.style.width = '100%';
				try {
					var res = JSON.parse(xhr.responseText);
					if (res.success) {
						status.textContent = '✓ نصب کامل شد';
						detail.textContent = res.data.message || '';
						result.innerHTML = '<p style="color:#2f9aa8;font-weight:700;">محتوای دمو با موفقیت نصب شد. <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank">مشاهده سایت</a></p>';
						setTimeout(function () { location.reload(); }, 1500);
					} else {
						status.textContent = 'خطا';
						detail.textContent = (res.data && res.data.message) || 'خطای ناشناخته';
						btn.disabled = false;
					}
				} catch (e) {
					status.textContent = 'خطا در پردازش پاسخ';
					detail.textContent = xhr.responseText.substring(0, 200);
					btn.disabled = false;
				}
			});
			xhr.addEventListener('error', function () {
				status.textContent = 'خطای شبکه';
				btn.disabled = false;
			});
			xhr.send(data.toString());
		});
	})();
	</script>
	<?php
}

/**
 * AJAX handler: install demo content.
 */
function khas_handle_install_demo() {
	check_ajax_referer( 'khas_demo', 'nonce' );
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( array( 'message' => 'دسترسی ندارید.' ) );
	}

	@set_time_limit( 300 ); // phpcs:ignore

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	try {
		khas_install_demo_content();
		update_option( 'khas_demo_installed', 1 );
		wp_send_json_success( array( 'message' => 'تمام محتوای دمو با موفقیت ساخته شد.' ) );
	} catch ( Exception $e ) {
		wp_send_json_error( array( 'message' => $e->getMessage() ) );
	}
}
add_action( 'wp_ajax_khas_install_demo', 'khas_handle_install_demo' );

/**
 * The actual installer logic.
 */
function khas_install_demo_content() {

	/* ---- 1) Categories with icons ---- */
	$categories = array(
		array( 'name' => 'مد و زیبایی',      'slug' => 'fashion',  'icon' => '👗' ),
		array( 'name' => 'خانه و دکوراسیون', 'slug' => 'home',     'icon' => '🛋️' ),
		array( 'name' => 'غذا و آشپزی',      'slug' => 'food',     'icon' => '🍲' ),
		array( 'name' => 'فرهنگ و هنر',      'slug' => 'culture',  'icon' => '🎭' ),
		array( 'name' => 'تکنولوژی',         'slug' => 'tech',     'icon' => '💻' ),
		array( 'name' => 'سفر و تجربه',      'slug' => 'travel',   'icon' => '✈️' ),
		array( 'name' => 'سلامت و تندرستی',  'slug' => 'health',   'icon' => '🧘' ),
		array( 'name' => 'مالی و کار',       'slug' => 'finance',  'icon' => '💼' ),
	);
	$cat_ids = array();
	foreach ( $categories as $c ) {
		$existing = term_exists( $c['slug'], 'category' );
		if ( $existing ) {
			$term_id = is_array( $existing ) ? (int) $existing['term_id'] : (int) $existing;
		} else {
			$res     = wp_insert_term( $c['name'], 'category', array( 'slug' => $c['slug'] ) );
			$term_id = is_wp_error( $res ) ? 0 : (int) $res['term_id'];
		}
		if ( $term_id ) {
			update_term_meta( $term_id, 'khas_icon', $c['icon'] );
			$cat_ids[ $c['slug'] ] = $term_id;
		}
	}

	/* ---- Replace default "Uncategorized" with first demo category, then delete it ---- */
	$first_demo_id = ! empty( $cat_ids ) ? reset( $cat_ids ) : 0;
	$default_id    = (int) get_option( 'default_category' );
	if ( $first_demo_id && $default_id && $default_id !== $first_demo_id ) {
		update_option( 'default_category', $first_demo_id );
		$default_term = get_term( $default_id, 'category' );
		if ( $default_term && ! is_wp_error( $default_term ) && 'uncategorized' === $default_term->slug ) {
			wp_delete_term( $default_id, 'category' );
		}
	}

	/* ---- 2) Delete Hello World default post ---- */
	$hello = get_page_by_title( 'Hello world!', OBJECT, 'post' );
	if ( ! $hello ) {
		$hello = get_page_by_title( 'سلام دنیا!', OBJECT, 'post' );
	}
	if ( $hello ) {
		wp_trash_post( $hello->ID );
	}

	/* ---- 3) Sample posts ---- */
	$IMG = array(
		'hero'     => 'https://images.pexels.com/photos/2174625/pexels-photo-2174625.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=900&w=1400',
		'minimal'  => 'https://images.pexels.com/photos/20285350/pexels-photo-20285350.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'beauty'   => 'https://images.pexels.com/photos/4426930/pexels-photo-4426930.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'travel'   => 'https://images.pexels.com/photos/31771435/pexels-photo-31771435.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'tech'     => 'https://images.pexels.com/photos/1350461/pexels-photo-1350461.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'meditate' => 'https://images.pexels.com/photos/4558326/pexels-photo-4558326.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'salad'    => 'https://images.pexels.com/photos/8120234/pexels-photo-8120234.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'finance'  => 'https://images.pexels.com/photos/6373128/pexels-photo-6373128.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'culture'  => 'https://images.pexels.com/photos/12776971/pexels-photo-12776971.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',

		'fashionBags'        => 'https://images.pexels.com/photos/1619655/pexels-photo-1619655.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'fashionFlatlay'     => 'https://images.pexels.com/photos/934064/pexels-photo-934064.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'fashionAccessories' => 'https://images.pexels.com/photos/5494377/pexels-photo-5494377.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'fashionBeautyKit'   => 'https://images.pexels.com/photos/3892371/pexels-photo-3892371.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'fashionWallet'      => 'https://images.pexels.com/photos/1132269/pexels-photo-1132269.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',

		'homeBedroom1'  => 'https://images.pexels.com/photos/28853343/pexels-photo-28853343.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'homeLivingRoom'=> 'https://images.pexels.com/photos/7404938/pexels-photo-7404938.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'homeBedroom2'  => 'https://images.pexels.com/photos/27164976/pexels-photo-27164976.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'homeNightstand'=> 'https://images.pexels.com/photos/27164979/pexels-photo-27164979.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'homeNeutral'   => 'https://images.pexels.com/photos/36411723/pexels-photo-36411723.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',

		'foodIngredients'=> 'https://images.pexels.com/photos/27177589/pexels-photo-27177589.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'foodPan'        => 'https://images.pexels.com/photos/4253311/pexels-photo-4253311.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'foodChef'       => 'https://images.pexels.com/photos/19300180/pexels-photo-19300180.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'foodChopping'   => 'https://images.pexels.com/photos/4252138/pexels-photo-4252138.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'foodTongs'      => 'https://images.pexels.com/photos/6910727/pexels-photo-6910727.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',

		'cultureGallery1'=> 'https://images.pexels.com/photos/20967/pexels-photo.jpg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'cultureGallery2'=> 'https://images.pexels.com/photos/34614/pexels-photo.jpg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'cultureGallery3'=> 'https://images.pexels.com/photos/30489691/pexels-photo-30489691.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'culturePhoto'   => 'https://images.pexels.com/photos/17038608/pexels-photo-17038608.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'cultureGallery4'=> 'https://images.pexels.com/photos/30489756/pexels-photo-30489756.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',

		'techLaptop'    => 'https://images.pexels.com/photos/265144/pexels-photo-265144.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'techHeadphones'=> 'https://images.pexels.com/photos/358103/pexels-photo-358103.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'techDesk'      => 'https://images.pexels.com/photos/5474290/pexels-photo-5474290.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'techPhone'     => 'https://images.pexels.com/photos/11129922/pexels-photo-11129922.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',

		'travelMonastery'=> 'https://images.pexels.com/photos/28825813/pexels-photo-28825813.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'travelValley'   => 'https://images.pexels.com/photos/24643976/pexels-photo-24643976.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'travelCoast'    => 'https://images.pexels.com/photos/28302996/pexels-photo-28302996.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'travelHallstatt'=> 'https://images.pexels.com/photos/14974644/pexels-photo-14974644.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'travelLake'     => 'https://images.pexels.com/photos/34032691/pexels-photo-34032691.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',

		'financeCharts'    => 'https://images.pexels.com/photos/7054416/pexels-photo-7054416.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'financePaperwork' => 'https://images.pexels.com/photos/7433839/pexels-photo-7433839.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'financeMeeting'   => 'https://images.pexels.com/photos/7793118/pexels-photo-7793118.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'financeDiscussion'=> 'https://images.pexels.com/photos/8068654/pexels-photo-8068654.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'financeMarket'    => 'https://images.pexels.com/photos/8353775/pexels-photo-8353775.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',

		'healthYogaPier' => 'https://images.pexels.com/photos/13849161/pexels-photo-13849161.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'healthStretch'  => 'https://images.pexels.com/photos/6493542/pexels-photo-6493542.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
		'healthGroupYoga'=> 'https://images.pexels.com/photos/868483/pexels-photo-868483.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=700&w=1100',
	);

	$posts = array(
		array(
			'title'    => 'مینیمالیسم شرقی؛ زیبایی در سادگی',
			'slug'     => 'eastern-minimalism-beauty-of-simplicity',
			'cat'      => 'home',
			'img'      => 'minimal',
			'author'   => 'نرگس امیری',
			'excerpt'  => 'چگونه با فلسفه مینیمالیسم در طراحی شرقی فضای زندگی خود را الهام‌بخش کنیم.',
			'sticky'   => true,
			'content'  => "مینیمالیسم شرقی تنها یک سبک دکوراسیون نیست؛ یک نگرش به زندگی است. این فلسفه به ما می‌آموزد که با حذف زوائد و تمرکز بر آنچه واقعاً اهمیت دارد، به آرامش و وضوح ذهنی دست پیدا کنیم.\n\n## فلسفه فضای خالی\n\nدر طراحی شرقی، فضای خالی به اندازه اشیا اهمیت دارد. این فضای تنفس به چشم اجازه می‌دهد استراحت کند و هر شیء ارزشمند بهتر دیده شود.\n\n## پالت رنگی خاکی و آرام\n\nرنگ‌های طبیعی مانند بژ، خاکستری روشن، قهوه‌ای ملایم و سفید گرم، پایه‌ی این سبک هستند.\n\n## مواد طبیعی و بافت\n\nچوب، سرامیک دست‌ساز، کتان و سنگ عناصری هستند که روح به فضا می‌بخشند.",
		),
		array(
			'title'   => 'ترندهای جواهرات بهار و تابستان ۱۴۰۳',
			'slug'    => 'spring-summer-jewelry-trends-1403',
			'cat'     => 'fashion',
			'img'     => 'beauty',
			'author'  => 'مانا یوسفی',
			'excerpt' => 'نگاهی به جسورانه‌ترین انتخاب‌های جواهرات فصل.',
			'content' => "امسال جواهرات به سمت طراحی‌های ارگانیک و الهام‌گرفته از طبیعت حرکت کرده‌اند.\n\n## طلای مات و بافت‌دار\n\nسطوح مات جای براقی‌های کلاسیک را گرفته‌اند.\n\n## سنگ‌های رنگی طبیعی\n\nفیروزه، عقیق و کوارتز صورتی در مرکز توجه قرار دارند.",
		),
		array(
			'title'   => 'بخارا، شهری که زمان در آن ایستاده',
			'slug'    => 'bukhara-city-where-time-stands-still',
			'cat'     => 'travel',
			'img'     => 'travel',
			'author'  => 'هومن رستمی',
			'excerpt' => 'سفری به دل جاده ابریشم؛ روایتی از معماری و بازارها.',
			'content' => "بخارا یکی از کهن‌ترین شهرهای آسیای مرکزی است که قدم زدن در کوچه‌های خاکی آن مانند سفر در زمان است.\n\n## معماری خشتی بی‌نظیر\n\nمدرسه‌ها، منارها و کاروانسراهای بخارا با آجرهای زرد رنگ خود، در نور غروب رنگی طلایی به خود می‌گیرند.\n\n## بازار سرپوشیده\n\nزیر گنبدهای قدیمی بازار، صنعتگران هنوز مشغول کار دستی هستند.",
		),
		array(
			'title'   => '۱۵ اپلیکیشن که هر روز زندگی شما را ساده‌تر می‌کنند',
			'slug'    => 'apps-that-simplify-your-day',
			'cat'     => 'tech',
			'img'     => 'tech',
			'author'  => 'سارا کاظمی',
			'excerpt' => 'مجموعه‌ای از بهترین ابزارهای دیجیتال برای مدیریت زمان.',
			'content' => "فناوری وقتی ارزشمند است که واقعاً زندگی را ساده کند.\n\n## مدیریت وظایف\n\nابزارهای ساده‌ی لیست کارها به شما کمک می‌کنند بدون استرس، اولویت‌ها را مشخص کنید.\n\n## تمرکز و آرامش\n\nاپلیکیشن‌های مدیتیشن و مدیریت زمان می‌توانند مرز میان کار و استراحت را روشن‌تر کنند.",
		),
		array(
			'title'   => 'عادت‌های صبحگاهی افراد موفق و پرانرژی',
			'slug'    => 'morning-habits-of-successful-people',
			'cat'     => 'health',
			'img'     => 'meditate',
			'author'  => 'الهام صادقی',
			'excerpt' => 'شروع روز چگونه می‌تواند مسیر کل روز شما را تغییر دهد؟',
			'content' => "افراد موفق معمولاً روز خود را با نظم و آگاهی آغاز می‌کنند.\n\n## بیداری بدون گوشی\n\nاولین ساعت روز را به خودتان اختصاص دهید، نه به شبکه‌های اجتماعی.\n\n## حرکت و نور طبیعی\n\nچند دقیقه کشش و قرار گرفتن در معرض نور صبحگاهی، ساعت بدن را تنظیم می‌کند.",
		),
		array(
			'title'   => 'طرز تهیه سالاد مدیترانه‌ای سالم و خوشمزه',
			'slug'    => 'healthy-refreshing-salad-recipe',
			'cat'     => 'food',
			'img'     => 'salad',
			'author'  => 'پریسا نوری',
			'excerpt' => 'یک دستور ساده و سریع برای وعده‌ای سبک و رنگارنگ.',
			'content' => "سالاد مدیترانه‌ای ترکیبی متعادل از سبزیجات تازه، روغن زیتون و طعم‌های ساده است.\n\n## مواد لازم\n\nگوجه گیلاسی، خیار، پیاز قرمز، زیتون، پنیر فتا و سبزیجات معطر.\n\n## سس ساده\n\nروغن زیتون بکر، آب لیمو تازه، کمی نمک و فلفل.",
		),
		array(
			'title'   => 'چگونه بهترین تشک برای خواب عمیق انتخاب کنیم؟',
			'slug'    => 'how-to-choose-mattress-for-deep-sleep',
			'cat'     => 'health',
			'img'     => 'minimal',
			'author'  => 'الهام صادقی',
			'excerpt' => 'راهنمای کامل انتخاب تشکی که کیفیت خواب شما را تضمین می‌کند.',
			'content' => "خواب باکیفیت با انتخاب تشک مناسب آغاز می‌شود.\n\n## نکات کلیدی\n\nبه سفتی، جنس و اندازه توجه کنید و حتماً پیش از خرید آن را امتحان کنید.",
		),
		array(
			'title'   => 'راهنمای خرید گوشی هوشمند در سال ۱۴۰۳',
			'slug'    => 'buying-smart-phone-guide-1403',
			'cat'     => 'tech',
			'img'     => 'techPhone',
			'author'  => 'سارا کاظمی',
			'excerpt' => 'همه چیز درباره‌ی انتخاب گوشی مناسب بودجه و نیاز شما.',
			'content' => "بازار گوشی‌های هوشمند بسیار متنوع است.\n\n## اولویت‌بندی نیازها\n\nپیش از خرید مشخص کنید چه ویژگی‌هایی برای شما واقعاً مهم هستند.",
		),
		array(
			'title'   => '۷ تمرین ساده برای تقویت عضلات در خانه',
			'slug'    => 'simple-exercises-to-strengthen-muscles-at-home',
			'cat'     => 'health',
			'img'     => 'healthGroupYoga',
			'author'  => 'بهزاد کریمی',
			'excerpt' => 'بدون نیاز به باشگاه و تجهیزات، بدنی قوی‌تر بسازید.',
			'content' => "تمرین در خانه می‌تواند به اندازه‌ی باشگاه مؤثر باشد.\n\n## تمرینات پایه\n\nشنا، اسکات و پلانک سه حرکت طلایی برای شروع هستند.",
		),
		array(
			'title'   => 'مدیریت بودجه شخصی در ۵ قدم عملی',
			'slug'    => 'personal-budget-management-in-5-steps',
			'cat'     => 'finance',
			'img'     => 'finance',
			'author'  => 'کاوه مرادی',
			'excerpt' => 'با یک برنامه ساده، کنترل مالی زندگی خود را به دست بگیرید.',
			'content' => "مدیریت مالی موفق درباره‌ی درآمد بیشتر نیست، بلکه درباره‌ی تصمیم‌های آگاهانه است.\n\n## قانون بودجه‌بندی\n\nدرآمد را میان نیازها، خواسته‌ها و پس‌انداز تقسیم کنید تا تعادل حفظ شود.",
		),
		array(
			'title'   => 'هنر نوشیدن چای و آیین ذهن‌آگاهی',
			'slug'    => 'art-of-tea-ceremony-and-mindfulness',
			'cat'     => 'culture',
			'img'     => 'culture',
			'author'  => 'نرگس امیری',
			'excerpt' => 'چگونه یک فنجان چای می‌تواند به لحظه‌ای از آرامش تبدیل شود.',
			'content' => "آیین چای در فرهنگ‌های مختلف، بیش از یک نوشیدنی است؛ تمرینی برای حضور در لحظه است.\n\n## مراسم ساده\n\nهر مرحله، از دم کردن تا نوشیدن، فرصتی برای آرام کردن ذهن است.",
		),

		/* ===== خانه و دکوراسیون (home) — ۵ مقاله بیشتر ===== */
		array(
			'title'   => 'چیدمان اتاق خواب برای خوابی آرام‌تر',
			'slug'    => 'bedroom-layout-for-deeper-sleep',
			'cat'     => 'home',
			'img'     => 'homeBedroom1',
			'author'  => 'نرگس امیری',
			'excerpt' => 'چند اصل ساده در نورپردازی، رنگ و چیدمان مبلمان که کیفیت خواب شبانه شما را بهبود می‌بخشد.',
			'content' => "اتاق خواب باید مکانی برای استراحت واقعی باشد، نه ادامه‌ی شلوغی روز.\n\n## نور گرم و قابل تنظیم\n\nاز نور مستقیم و سرد پرهیز کنید. چراغ‌های رومیزی با نور زرد فضا را برای خواب آماده می‌کنند.\n\n## کاهش محرک‌های بصری\n\nکمد بسته، میز کار جدا از تخت و رنگ‌های آرام دیوار، ذهن را برای استراحت آماده می‌کند.",
		),
		array(
			'title'   => 'گیاهان آپارتمانی که نگهداری آسانی دارند',
			'slug'    => 'easy-care-indoor-plants',
			'cat'     => 'home',
			'img'     => 'homeLivingRoom',
			'author'  => 'پریسا نوری',
			'excerpt' => 'معرفی گیاهانی که با کمترین مراقبت، سرسبزی و آرامش را به فضای خانه شما اضافه می‌کنند.',
			'content' => "داشتن گیاه در خانه، علاوه بر زیبایی بصری، کیفیت هوا را نیز بهبود می‌بخشد.\n\n## گیاهان مقاوم پیشنهادی\n\nسانسوریا، پوتوس و زامایفولیا نیاز کمی به نور مستقیم و آبیاری مکرر دارند.\n\n## محل قرارگیری مناسب\n\nنزدیک پنجره اما دور از نور مستقیم آفتاب ظهر، بهترین مکان است.",
		),
		array(
			'title'   => 'نورپردازی هوشمند برای فضای گرم‌تر',
			'slug'    => 'smart-lighting-for-warmer-spaces',
			'cat'     => 'home',
			'img'     => 'homeBedroom2',
			'author'  => 'نرگس امیری',
			'excerpt' => 'چگونه با لایه‌بندی نور، خانه‌ای گرم‌تر، دنج‌تر و شخصی‌تر بسازیم.',
			'content' => "نور یکی از مهم‌ترین و کم‌هزینه‌ترین عناصر دکوراسیون است.\n\n## سه لایه نور\n\nنور عمومی، نور کاربردی و نور تزئینی را ترکیب کنید تا فضایی متعادل داشته باشید.\n\n## دمای رنگ نور\n\nنور با دمای رنگ گرم حس صمیمیت بیشتری نسبت به نور سفید و سرد ایجاد می‌کند.",
		),
		array(
			'title'   => 'ترکیب سبک‌های کلاسیک و مدرن در دکوراسیون',
			'slug'    => 'mixing-classic-and-modern-decor',
			'cat'     => 'home',
			'img'     => 'homeNightstand',
			'author'  => 'پریسا نوری',
			'excerpt' => 'چطور می‌توان مبلمان قدیمی و عناصر مدرن را در یک فضای هماهنگ کنار هم قرار داد.',
			'content' => "ترکیب سبک‌ها اگر با دقت انجام شود، به فضای خانه شخصیت و عمق می‌بخشد.\n\n## یک عنصر قدیمی، پس‌زمینه مدرن\n\nیک تکه مبلمان کلاسیک در فضایی با خطوط ساده، بیشتر به چشم می‌آید.\n\n## تعادل از طریق رنگ\n\nپالت رنگی مشترک، پل ارتباطی میان دو سبک متفاوت است.",
		),
		array(
			'title'   => 'سازماندهی فضای کوچک؛ ترفندهای کاربردی',
			'slug'    => 'organizing-small-spaces',
			'cat'     => 'home',
			'img'     => 'homeNeutral',
			'author'  => 'نرگس امیری',
			'excerpt' => 'راهکارهایی ساده برای استفاده بهینه از هر متر مربع در آپارتمان‌های کوچک.',
			'content' => "فضای کوچک به معنای محدودیت در سبک نیست؛ با برنامه‌ریزی درست می‌توان از هر گوشه استفاده کرد.\n\n## مبلمان چندمنظوره\n\nتخت‌های دارای کشو و مبل‌های تبدیل‌شونده، فضای ذخیره‌سازی پنهانی ایجاد می‌کنند.\n\n## استفاده از ارتفاع دیوار\n\nقفسه‌های عمودی، فضای کف را آزاد نگه می‌دارند.",
		),

		/* ===== مد و زیبایی (fashion) — ۵ مقاله بیشتر ===== */
		array(
			'title'   => 'راهنمای انتخاب کیف دستی مناسب برای هر مناسبت',
			'slug'    => 'choosing-the-right-handbag-for-every-occasion',
			'cat'     => 'fashion',
			'img'     => 'fashionBags',
			'author'  => 'مانا یوسفی',
			'excerpt' => 'از کیف‌های روزمره تا مدل‌های رسمی؛ چگونه کیفی متناسب با سبک زندگی خود انتخاب کنیم.',
			'content' => "کیف دستی مناسب، مکمل نهایی هر استایل است.\n\n## کیف روزمره\n\nجنس مقاوم و اندازه متوسط برای حمل وسایل ضروری، اولویت اول است.\n\n## کیف مناسبتی\n\nبرای مناسبت‌های رسمی، مدل‌های کوچک‌تر با جزئیات ظریف‌تر انتخاب بهتری هستند.",
		),
		array(
			'title'   => 'رنگ‌های پاییزی که امسال باید در کمد لباس داشته باشید',
			'slug'    => 'autumn-colors-for-your-wardrobe',
			'cat'     => 'fashion',
			'img'     => 'fashionFlatlay',
			'author'  => 'مانا یوسفی',
			'excerpt' => 'پالت رنگی گرم پاییزی که به هر پوست و سبکی می‌آید.',
			'content' => "رنگ‌های پاییزی همیشه حس گرما و صمیمیت خاصی دارند.\n\n## قهوه‌ای شکلاتی و کرم\n\nاین ترکیب کلاسیک هرگز از مد نمی‌افتد.\n\n## سبز زیتونی\n\nاین رنگ خنثی و جذاب، به‌راحتی با دیگر رنگ‌های پاییزی ترکیب می‌شود.",
		),
		array(
			'title'   => 'عطرهای ماندگار برای فصل سرد',
			'slug'    => 'long-lasting-perfumes-for-cold-season',
			'cat'     => 'fashion',
			'img'     => 'fashionAccessories',
			'author'  => 'مانا یوسفی',
			'excerpt' => 'چگونه عطری انتخاب کنیم که در هوای سرد هم رایحه‌اش تا پایان روز باقی بماند.',
			'content' => "در هوای سرد، رایحه‌های گرم و ادویه‌ای بهتر ماندگار می‌شوند.\n\n## نت‌های پایه قوی\n\nعطرهایی با نت‌های چوبی و کهربایی در سرما ماندگاری بیشتری دارند.\n\n## محل درست اسپری\n\nزدن عطر روی نقاط نبض، پخش رایحه را بهتر می‌کند.",
		),
		array(
			'title'   => 'اکسسوری‌های مینیمال؛ کمتر بیشتر است',
			'slug'    => 'minimal-accessories-less-is-more',
			'cat'     => 'fashion',
			'img'     => 'fashionBeautyKit',
			'author'  => 'مانا یوسفی',
			'excerpt' => 'چگونه با چند قطعه ساده و باکیفیت، استایلی شیک و بی‌زمان بسازیم.',
			'content' => "فلسفه مینیمال در اکسسوری به معنای انتخاب چند قطعه اصیل است.\n\n## قطعات چندمنظوره\n\nدستبند یا گردنبندی که با چند استایل هماهنگ باشد، ارزش بیشتری دارد.",
		),
		array(
			'title'   => 'مراقبت از پوست در فصول مختلف سال',
			'slug'    => 'skincare-through-the-seasons',
			'cat'     => 'fashion',
			'img'     => 'fashionWallet',
			'author'  => 'مانا یوسفی',
			'excerpt' => 'چگونه روتین پوستی خود را با تغییر فصل تطبیق دهیم تا پوستی سالم داشته باشیم.',
			'content' => "نیاز پوست در هر فصل متفاوت است.\n\n## فصل سرد\n\nاستفاده از مرطوب‌کننده‌های غلیظ‌تر از خشکی پوست جلوگیری می‌کند.\n\n## فصل گرم\n\nژل‌های سبک و ضدآفتاب با SPF بالا، اولویت اصلی هستند.",
		),

		/* ===== غذا و آشپزی (food) — ۵ مقاله بیشتر ===== */
		array(
			'title'   => 'صبحانه‌های سالم و سریع برای صبح‌های شلوغ',
			'slug'    => 'quick-healthy-breakfasts-for-busy-mornings',
			'cat'     => 'food',
			'img'     => 'foodIngredients',
			'author'  => 'پریسا نوری',
			'excerpt' => 'ایده‌هایی برای شروع روز با انرژی، حتی وقتی وقت کافی ندارید.',
			'content' => "صبحانه‌ای سالم نیازی به زمان زیاد ندارد.\n\n## آماده‌سازی شبانه\n\nجوی دوسر خیسانده‌شده یا اسموتی‌های از پیش آماده، صبح شما را ساده‌تر می‌کنند.\n\n## ترکیب پروتئین و فیبر\n\nتخم‌مرغ، ماست یونانی و میوه‌های تازه انرژی پایدار تأمین می‌کنند.",
		),
		array(
			'title'   => 'راز پخت نان خانگی کامل',
			'slug'    => 'secret-to-perfect-homemade-bread',
			'cat'     => 'food',
			'img'     => 'foodPan',
			'author'  => 'پریسا نوری',
			'excerpt' => 'نکاتی که تفاوت میان نان خانگی معمولی و نانی حرفه‌ای را رقم می‌زند.',
			'content' => "پخت نان خانگی هنری است که با صبر و رعایت چند نکته کلیدی، به نتیجه‌ای حرفه‌ای می‌رسد.\n\n## زمان استراحت خمیر\n\nعجله در فرآیند تخمیر، بزرگ‌ترین اشتباه است.\n\n## دمای فر\n\nپیش‌گرم کردن کامل فر پیش از قرار دادن نان، به شکل‌گیری پوسته کمک می‌کند.",
		),
		array(
			'title'   => 'نوشیدنی‌های گرم برای عصرهای پاییزی',
			'slug'    => 'warm-drinks-for-autumn-evenings',
			'cat'     => 'food',
			'img'     => 'foodChef',
			'author'  => 'پریسا نوری',
			'excerpt' => 'چند دستور ساده برای نوشیدنی‌های دلچسب که فضای خانه را گرم‌تر می‌کنند.',
			'content' => "نوشیدنی گرم، یکی از ساده‌ترین راه‌ها برای ایجاد حس آرامش در عصرهای سرد است.\n\n## دارچین و زنجبیل\n\nدمنوش‌های ادویه‌ای با دارچین و زنجبیل، هم طعم گرمی دارند و هم به هضم غذا کمک می‌کنند.",
		),
		array(
			'title'   => 'آشپزی با مواد اولیه فصلی',
			'slug'    => 'cooking-with-seasonal-ingredients',
			'cat'     => 'food',
			'img'     => 'foodChopping',
			'author'  => 'پریسا نوری',
			'excerpt' => 'چرا استفاده از محصولات فصل، هم به طعم غذا کمک می‌کند و هم مقرون‌به‌صرفه‌تر است.',
			'content' => "مواد اولیه فصلی معمولاً تازه‌تر، خوش‌طعم‌تر و ارزان‌تر هستند.\n\n## خرید از بازار محلی\n\nمیوه و سبزی محلی و فصلی، کمترین فاصله را از مزرعه تا آشپزخانه طی کرده‌اند.\n\n## برنامه‌ریزی منو\n\nتنظیم منوی هفتگی بر اساس محصولات فصل، خلاقیت را افزایش می‌دهد.",
		),
		array(
			'title'   => 'دسرهای ساده برای مهمانی‌های خانگی',
			'slug'    => 'simple-desserts-for-home-gatherings',
			'cat'     => 'food',
			'img'     => 'foodTongs',
			'author'  => 'پریسا نوری',
			'excerpt' => 'چند دسر بدون پخت که در کمترین زمان آماده می‌شوند و مهمانان را غافلگیر می‌کنند.',
			'content' => "دسر لزوماً نباید پیچیده باشد.\n\n## دسرهای بدون پخت\n\nتیرامیسوی ساده یا پارفی میوه‌ای، بدون نیاز به فر، در کمتر از نیم ساعت آماده می‌شوند.",
		),

		/* ===== فرهنگ و هنر (culture) — ۵ مقاله بیشتر ===== */
		array(
			'title'   => 'سینمای مستقل ایران؛ نگاهی به آثار برجسته',
			'slug'    => 'iranian-independent-cinema-highlights',
			'cat'     => 'culture',
			'img'     => 'cultureGallery1',
			'author'  => 'هومن رستمی',
			'excerpt' => 'مروری بر فیلم‌هایی که با بودجه کم، روایت‌هایی عمیق و ماندگار ساخته‌اند.',
			'content' => "سینمای مستقل ایران با وجود محدودیت‌ها، آثاری تحسین‌شده در سطح جهانی خلق کرده است.\n\n## روایت‌های انسانی\n\nبسیاری از این آثار به زندگی روزمره مردم عادی می‌پردازند.\n\n## تأثیر بر سینمای جهان\n\nکارگردانان ایرانی جوایز متعددی از جشنواره‌های معتبر گرفته‌اند.",
		),
		array(
			'title'   => 'موسیقی سنتی و تأثیر آن بر آرامش ذهن',
			'slug'    => 'traditional-music-and-its-calming-effect',
			'cat'     => 'culture',
			'img'     => 'cultureGallery2',
			'author'  => 'نرگس امیری',
			'excerpt' => 'چگونه گوش دادن به موسیقی سنتی می‌تواند استرس روزانه را کاهش دهد.',
			'content' => "موسیقی سنتی با ریتم‌های آرام و ملودی‌های عمیق، اثری ملموس بر کاهش اضطراب دارد.\n\n## سازهای بدون کلام\n\nموسیقی بی‌کلام سنتی، بستری آرام برای تمرکز و مدیتیشن فراهم می‌کند.",
		),
		array(
			'title'   => 'کتاب‌هایی که باید امسال بخوانید',
			'slug'    => 'books-you-should-read-this-year',
			'cat'     => 'culture',
			'img'     => 'cultureGallery3',
			'author'  => 'هومن رستمی',
			'excerpt' => 'فهرستی کوتاه از آثار داستانی و غیرداستانی که ارزش وقت گذاشتن دارند.',
			'content' => "مطالعه یکی از بهترین راه‌ها برای گسترش دیدگاه و آرامش ذهنی است.\n\n## داستان‌های معاصر\n\nرمان‌های نویسندگان معاصر، روایت‌هایی تازه از زندگی امروز ارائه می‌دهند.\n\n## کتاب‌های غیرداستانی\n\nآثار مرتبط با روان‌شناسی، ابزارهای عملی برای بهبود روزمرگی ارائه می‌کنند.",
		),
		array(
			'title'   => 'معماری ایرانی و راز ماندگاری آن',
			'slug'    => 'persian-architecture-and-its-timelessness',
			'cat'     => 'culture',
			'img'     => 'culturePhoto',
			'author'  => 'هومن رستمی',
			'excerpt' => 'چرا بناهای تاریخی ایران پس از قرن‌ها همچنان الهام‌بخش معماران امروز هستند.',
			'content' => "معماری ایرانی ترکیبی از زیبایی، کارکرد و هماهنگی با اقلیم است.\n\n## هماهنگی با اقلیم\n\nبادگیرها، حیاط مرکزی و مصالح بومی، پاسخی هوشمندانه به شرایط آب‌وهوایی بودند.\n\n## تناسبات هندسی\n\nاستفاده از الگوهای هندسی دقیق، حس تعادل و آرامش را منتقل می‌کند.",
		),
		array(
			'title'   => 'هنر خوشنویسی؛ از سنت تا مدرنیته',
			'slug'    => 'art-of-calligraphy-tradition-to-modern',
			'cat'     => 'culture',
			'img'     => 'cultureGallery4',
			'author'  => 'نرگس امیری',
			'excerpt' => 'سفری کوتاه در تاریخ خوشنویسی فارسی و جایگاه آن در طراحی امروز.',
			'content' => "خوشنویسی فارسی هنری است که مرز میان نوشتار و نقاشی را از میان برمی‌دارد.\n\n## از کتیبه تا برند\n\nامروز بسیاری از برندها از خطوط سنتی برای ایجاد هویت بصری اصیل استفاده می‌کنند.",
		),

		/* ===== تکنولوژی (tech) — ۳ مقاله بیشتر ===== */
		array(
			'title'   => 'هوش مصنوعی و تأثیر آن بر زندگی روزمره',
			'slug'    => 'ai-impact-on-daily-life',
			'cat'     => 'tech',
			'img'     => 'techLaptop',
			'author'  => 'سارا کاظمی',
			'excerpt' => 'چگونه ابزارهای هوش مصنوعی به‌آرامی وارد تصمیم‌های کوچک روزانه ما شده‌اند.',
			'content' => "هوش مصنوعی دیگر مفهومی آینده‌نگرانه نیست؛ بخشی از تجربه روزمره ما شده است.\n\n## دستیارهای هوشمند\n\nاز پیشنهاد محتوا تا برنامه‌ریزی روزانه، دستیارهای هوش مصنوعی تصمیم‌گیری را سریع‌تر کرده‌اند.\n\n## چالش‌های پیش رو\n\nهمراه با مزایا، مسائلی مانند حریم خصوصی داده اهمیت بیشتری پیدا کرده‌اند.",
		),
		array(
			'title'   => 'بهترین ابزارهای کار از راه دور',
			'slug'    => 'best-tools-for-remote-work',
			'cat'     => 'tech',
			'img'     => 'techHeadphones',
			'author'  => 'سارا کاظمی',
			'excerpt' => 'مجموعه‌ای از نرم‌افزارها که همکاری تیمی از راه دور را ساده‌تر می‌کنند.',
			'content' => "کار از راه دور به ابزارهای درستی نیاز دارد تا ارتباط و بهره‌وری تیم حفظ شود.\n\n## ارتباط تیمی\n\nپیام‌رسان‌ها و ابزارهای ویدئوکنفرانس، جایگزین مناسبی برای تعاملات حضوری فراهم کرده‌اند.\n\n## مدیریت پروژه\n\nبردهای وظیفه آنلاین، شفافیت پیشرفت کار را افزایش می‌دهند.",
		),
		array(
			'title'   => 'امنیت دیجیتال؛ محافظت از حریم خصوصی آنلاین',
			'slug'    => 'digital-security-protecting-your-privacy',
			'cat'     => 'tech',
			'img'     => 'techDesk',
			'author'  => 'سارا کاظمی',
			'excerpt' => 'چند عادت ساده که امنیت اطلاعات شخصی شما را در فضای آنلاین افزایش می‌دهد.',
			'content' => "با افزایش وابستگی به فضای دیجیتال، محافظت از اطلاعات شخصی اهمیت پیدا کرده است.\n\n## رمز عبور قوی\n\nاستفاده از رمزهای یکتا و احراز هویت دومرحله‌ای، اولین خط دفاعی است.\n\n## به‌روزرسانی منظم\n\nبه‌روز نگه‌داشتن نرم‌افزارها، بسیاری از حفره‌های امنیتی را می‌بندد.",
		),
		array(
			'title'   => 'ساعت‌های هوشمند؛ کدام مدل مناسب شماست؟',
			'slug'    => 'smartwatches-which-model-suits-you',
			'cat'     => 'tech',
			'img'     => 'techPhone',
			'author'  => 'سارا کاظمی',
			'excerpt' => 'مقایسه‌ای کوتاه برای انتخاب ساعت هوشمند بر اساس سبک زندگی شما.',
			'content' => "ساعت هوشمند مناسب به نیاز شما بستگی دارد؛ از پیگیری تناسب اندام تا مدیریت اعلان‌ها.\n\n## برای ورزشکاران\n\nمدل‌هایی با سنجش دقیق ضربان قلب و GPS داخلی، انتخاب بهتری برای فعالیت‌های ورزشی هستند.",
		),

		/* ===== سفر و تجربه (travel) — ۵ مقاله بیشتر ===== */
		array(
			'title'   => 'کوهستان‌های اروپا؛ مقصدی برای عاشقان طبیعت',
			'slug'    => 'european-mountains-for-nature-lovers',
			'cat'     => 'travel',
			'img'     => 'travelMonastery',
			'author'  => 'هومن رستمی',
			'excerpt' => 'معرفی چند منطقه کوهستانی کمتر شناخته‌شده برای فرار از شلوغی مقاصد معروف.',
			'content' => "کوهستان‌های اروپا فراتر از مقاصد مشهور، مناطقی آرام و بکر نیز دارند.\n\n## مسیرهای پیاده‌روی\n\nبسیاری از این مناطق مسیرهای علامت‌گذاری‌شده برای همه سطوح دارند.\n\n## بهترین فصل سفر\n\nاواخر بهار تا اوایل پاییز، بهترین زمان بدون ازدحام است.",
		),
		array(
			'title'   => 'سفر تنها؛ تجربه‌ای برای شناخت خود',
			'slug'    => 'solo-travel-a-journey-of-self-discovery',
			'cat'     => 'travel',
			'img'     => 'travelValley',
			'author'  => 'هومن رستمی',
			'excerpt' => 'چرا سفر انفرادی می‌تواند تجربه‌ای متحول‌کننده و آموزنده باشد.',
			'content' => "سفر تنها فرصتی است برای شنیدن صدای درونی خود.\n\n## آزادی در تصمیم‌گیری\n\nمی‌توانید برنامه سفر را کاملاً بر اساس علاقه خود تنظیم کنید.\n\n## آشنایی با افراد جدید\n\nسفر انفرادی معمولاً باعث می‌شود راحت‌تر با مسافران و مردم محلی ارتباط برقرار کنید.",
		),
		array(
			'title'   => 'روستاهای ساحلی که کمتر دیده شده‌اند',
			'slug'    => 'hidden-coastal-villages-worth-visiting',
			'cat'     => 'travel',
			'img'     => 'travelCoast',
			'author'  => 'هومن رستمی',
			'excerpt' => 'چند روستای ساحلی آرام که هنوز از دید گردشگران انبوه دور مانده‌اند.',
			'content' => "دور از سواحل شلوغ، روستاهای ساحلی کوچکی وجود دارند که سادگی را حفظ کرده‌اند.\n\n## زندگی محلی اصیل\n\nدر این روستاها هنوز می‌توان شاهد سبک زندگی سنتی ماهیگیری بود.",
		),
		array(
			'title'   => 'راهنمای سفر ارزان بدون افت کیفیت',
			'slug'    => 'budget-travel-without-losing-quality',
			'cat'     => 'travel',
			'img'     => 'travelHallstatt',
			'author'  => 'هومن رستمی',
			'excerpt' => 'ترفندهایی برای کاهش هزینه سفر بدون این‌که از تجربه چیزی کم شود.',
			'content' => "سفر ارزان به معنای سفر کم‌کیفیت نیست.\n\n## زمان‌بندی سفر\n\nسفر در فصل غیرپیک، هم هزینه را کاهش می‌دهد و هم ازدحام کمتری دارد.\n\n## اقامت جایگزین\n\nاقامتگاه‌های بومی، اغلب تجربه‌ای اصیل‌تر و ارزان‌تر ارائه می‌دهند.",
		),
		array(
			'title'   => 'دریاچه‌های رویایی برای فرار از شهر',
			'slug'    => 'dreamy-lakes-to-escape-the-city',
			'cat'     => 'travel',
			'img'     => 'travelLake',
			'author'  => 'هومن رستمی',
			'excerpt' => 'چند دریاچه آرام که مقصدی عالی برای یک اقامت کوتاه و آرامش‌بخش هستند.',
			'content' => "کنار دریاچه بودن، یکی از ساده‌ترین راه‌ها برای بازیابی آرامش ذهنی است.\n\n## بهترین زمان بازدید\n\nصبح زود یا نزدیک غروب، بهترین نور و آرام‌ترین فضا را فراهم می‌کند.",
		),

		/* ===== سلامت و تندرستی (health) — ۳ مقاله بیشتر ===== */
		array(
			'title'   => 'تنفس آگاهانه؛ تمرینی ساده برای کاهش استرس',
			'slug'    => 'mindful-breathing-for-stress-relief',
			'cat'     => 'health',
			'img'     => 'healthYogaPier',
			'author'  => 'الهام صادقی',
			'excerpt' => 'چگونه چند دقیقه تمرین تنفس می‌تواند اضطراب روزانه را به‌طور محسوسی کاهش دهد.',
			'content' => "تنفس آگاهانه یکی از ساده‌ترین ابزارها برای مدیریت استرس است.\n\n## تکنیک تنفس ۴-۷-۸\n\nچهار ثانیه دم، هفت ثانیه نگه‌داشتن نفس و هشت ثانیه بازدم، سیستم عصبی را آرام می‌کند.",
		),
		array(
			'title'   => 'تغذیه متعادل برای انرژی بیشتر در طول روز',
			'slug'    => 'balanced-nutrition-for-more-energy',
			'cat'     => 'health',
			'img'     => 'healthStretch',
			'author'  => 'بهزاد کریمی',
			'excerpt' => 'اصول ساده تغذیه‌ای که سطح انرژی شما را در طول روز پایدار نگه می‌دارد.',
			'content' => "افت انرژی در طول روز اغلب نتیجه الگوی نامتعادل غذایی است.\n\n## وعده‌های کوچک و منظم\n\nوعده‌های کوچک‌تر و منظم قند خون را پایدار نگه می‌دارد.\n\n## کربوهیدرات‌های پیچیده\n\nغلات کامل و حبوبات، انرژی پایدارتری فراهم می‌کنند.",
		),
		array(
			'title'   => 'اهمیت خواب کافی برای سلامت ذهن',
			'slug'    => 'importance-of-adequate-sleep-for-mental-health',
			'cat'     => 'health',
			'img'     => 'meditate',
			'author'  => 'الهام صادقی',
			'excerpt' => 'چرا کیفیت خواب مستقیماً بر تمرکز، خلق‌وخو و سلامت روان تأثیر می‌گذارد.',
			'content' => "خواب کافی فرآیندی حیاتی برای پردازش احساسات و بازسازی ذهنی است.\n\n## اثر کمبود خواب\n\nحتی یک شب کم‌خوابی می‌تواند تمرکز و کنترل احساسات را مختل کند.",
		),

		/* ===== مالی و کار (finance) — ۵ مقاله بیشتر ===== */
		array(
			'title'   => 'سرمایه‌گذاری هوشمند برای مبتدیان',
			'slug'    => 'smart-investing-for-beginners',
			'cat'     => 'finance',
			'img'     => 'financeCharts',
			'author'  => 'کاوه مرادی',
			'excerpt' => 'اصول پایه‌ای که پیش از شروع هر نوع سرمایه‌گذاری باید بدانید.',
			'content' => "سرمایه‌گذاری موفق نیازمند دانش پایه و صبر است.\n\n## تنوع‌بخشی به سبد\n\nپخش سرمایه میان چند دارایی مختلف، ریسک کلی را کاهش می‌دهد.\n\n## افق زمانی بلندمدت\n\nسرمایه‌گذاری با دید بلندمدت، در برابر نوسانات کوتاه‌مدت مقاوم‌تر است.",
		),
		array(
			'title'   => 'مهارت‌های مذاکره در محیط کار',
			'slug'    => 'negotiation-skills-in-the-workplace',
			'cat'     => 'finance',
			'img'     => 'financeMeeting',
			'author'  => 'کاوه مرادی',
			'excerpt' => 'چگونه با اعتمادبه‌نفس و احترام متقابل، در مذاکرات شغلی به نتیجه بهتر برسیم.',
			'content' => "مذاکره مهارتی است که با تمرین بهبود می‌یابد.\n\n## آماده‌سازی پیش از مذاکره\n\nشناخت دقیق ارزش خود و تحقیق درباره بازار، قدرت چانه‌زنی را افزایش می‌دهد.\n\n## گوش دادن فعال\n\nدرک نیاز طرف مقابل، راه را برای توافقی برد-برد هموار می‌کند.",
		),
		array(
			'title'   => 'چگونه از حقوق خود پس‌انداز کنیم؟',
			'slug'    => 'how-to-save-from-your-salary',
			'cat'     => 'finance',
			'img'     => 'financePaperwork',
			'author'  => 'کاوه مرادی',
			'excerpt' => 'روش‌های عملی برای کنار گذاشتن بخشی از درآمد، حتی با حقوق محدود.',
			'content' => "پس‌انداز کردن بیشتر به عادت بستگی دارد تا میزان درآمد.\n\n## پس‌انداز خودکار\n\nانتقال خودکار بخشی از حقوق به حساب پس‌انداز، از خرج شدن آن جلوگیری می‌کند.",
		),
		array(
			'title'   => 'تعادل کار و زندگی؛ چرا مهم است؟',
			'slug'    => 'work-life-balance-why-it-matters',
			'cat'     => 'finance',
			'img'     => 'financeDiscussion',
			'author'  => 'کاوه مرادی',
			'excerpt' => 'چگونه مرز میان کار و زندگی شخصی را حفظ کنیم بدون افت بهره‌وری.',
			'content' => "تعادل کار و زندگی عاملی مستقیم در سلامت روان و کیفیت عملکرد شغلی است.\n\n## تعیین مرزهای مشخص\n\nمشخص کردن ساعات کاری و پایبندی به آن‌ها، از فرسودگی شغلی جلوگیری می‌کند.\n\n## اهمیت زمان استراحت\n\nاستراحت واقعی، برای بازیابی انرژی ذهنی ضروری است.",
		),
		array(
			'title'   => 'کارآفرینی خانگی؛ از ایده تا اجرا',
			'slug'    => 'home-based-entrepreneurship-idea-to-execution',
			'cat'     => 'finance',
			'img'     => 'financeMarket',
			'author'  => 'کاوه مرادی',
			'excerpt' => 'مراحل عملی برای شروع یک کسب‌وکار کوچک خانگی بدون سرمایه اولیه زیاد.',
			'content' => "بسیاری از کسب‌وکارهای موفق امروز، از یک ایده ساده در خانه آغاز شده‌اند.\n\n## شروع کوچک\n\nآزمایش ایده در مقیاس کوچک، ریسک شکست را کاهش می‌دهد.\n\n## استفاده از فضای آنلاین\n\nشبکه‌های اجتماعی، امکان دسترسی به مشتریان را بدون فروشگاه فیزیکی فراهم کرده‌اند.",
		),
	);

	$post_ids = array();
	foreach ( $posts as $i => $p ) {
		$existing = get_page_by_path( $p['slug'], OBJECT, 'post' );
		if ( $existing ) {
			$post_id = $existing->ID;
		} else {
			$post_id = wp_insert_post(
				array(
					'post_title'   => $p['title'],
					'post_name'    => $p['slug'],
					'post_content' => $p['content'],
					'post_excerpt' => $p['excerpt'],
					'post_status'  => 'publish',
					'post_type'    => 'post',
					'post_date'    => gmdate( 'Y-m-d H:i:s', time() - ( $i * 86400 ) ),
				),
				true
			);
			if ( is_wp_error( $post_id ) ) {
				continue;
			}
		}

		// Category.
		if ( isset( $cat_ids[ $p['cat'] ] ) ) {
			wp_set_post_terms( $post_id, array( $cat_ids[ $p['cat'] ] ), 'category' );
		}

		// Featured image (sideload only if missing).
		if ( ! has_post_thumbnail( $post_id ) && ! empty( $IMG[ $p['img'] ] ) ) {
			$attachment_id = khas_sideload_image( $IMG[ $p['img'] ], $post_id, $p['title'] );
			if ( $attachment_id ) {
				set_post_thumbnail( $post_id, $attachment_id );
			}
		}

		// Author by display name (create if missing).
		if ( ! empty( $p['author'] ) ) {
			$user = get_user_by( 'display_name', $p['author'] );
			if ( ! $user ) {
				$slug    = sanitize_user( 'khas_' . $i );
				$user_id = wp_create_user( $slug . '@khas.local', wp_generate_password( 16 ), $slug . '@khas.local' );
				if ( ! is_wp_error( $user_id ) ) {
					wp_update_user(
						array(
							'ID'           => $user_id,
							'display_name' => $p['author'],
							'nickname'     => $p['author'],
							'first_name'   => $p['author'],
						)
					);
					$user = get_user_by( 'id', $user_id );
				}
			}
			if ( $user ) {
				wp_update_post(
					array(
						'ID'            => $post_id,
						'post_author'   => $user->ID,
					)
				);
			}
		}

		$post_ids[] = $post_id;

		// Sticky?
		if ( ! empty( $p['sticky'] ) ) {
			stick_post( $post_id );
		}
	}

	/* ---- 4) Create Home page & set as front page ---- */
	$home = get_page_by_path( 'home', OBJECT, 'page' );
	if ( ! $home ) {
		$home_id = wp_insert_post(
			array(
				'post_title'   => 'خانه',
				'post_name'    => 'home',
				'post_content' => '',
				'post_status'  => 'publish',
				'post_type'    => 'page',
			)
		);
	} else {
		$home_id = $home->ID;
	}
	if ( $home_id && ! is_wp_error( $home_id ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_id );
	}

	/* ---- 5) Primary menu with category links ---- */
	$menu_name = 'منوی اصلی خاص';
	$menu_obj  = wp_get_nav_menu_object( $menu_name );
	if ( ! $menu_obj ) {
		$menu_id = wp_create_nav_menu( $menu_name );
		if ( ! is_wp_error( $menu_id ) ) {
			foreach ( $categories as $c ) {
				if ( isset( $cat_ids[ $c['slug'] ] ) ) {
					wp_update_nav_menu_item(
						$menu_id,
						0,
						array(
							'menu-item-title'     => $c['name'],
							'menu-item-object'    => 'category',
							'menu-item-object-id' => $cat_ids[ $c['slug'] ],
							'menu-item-type'      => 'taxonomy',
							'menu-item-status'    => 'publish',
						)
					);
				}
			}
			$locations              = get_theme_mod( 'nav_menu_locations', array() );
			$locations['primary']   = $menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}
}

/**
 * Sideload an image from URL and return its attachment ID.
 *
 * @param string $url     Remote image URL.
 * @param int    $post_id Parent post ID.
 * @param string $title   Title for the attachment.
 * @return int|false Attachment ID or false on failure.
 */
function khas_sideload_image( $url, $post_id, $title = '' ) {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = download_url( $url, 30 );
	if ( is_wp_error( $tmp ) ) {
		return false;
	}

	$file_array = array(
		'name'     => basename( parse_url( $url, PHP_URL_PATH ) ),
		'tmp_name' => $tmp,
	);

	$attachment_id = media_handle_sideload( $file_array, $post_id, $title );
	if ( is_wp_error( $attachment_id ) ) {
		@unlink( $tmp ); // phpcs:ignore
		return false;
	}
	return (int) $attachment_id;
}
