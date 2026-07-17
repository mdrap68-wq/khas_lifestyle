<?php
/**
 * Header template.
 *
 * @package KHAS
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<link rel="icon" type="image/png" href="<?php echo esc_url( get_template_directory_uri() . '/assets/brand/khas-lifestyle-logo.png' ); ?>" />
	<link rel="apple-touch-icon" href="<?php echo esc_url( get_template_directory_uri() . '/assets/brand/khas-lifestyle-logo.png' ); ?>" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="khas-header<?php echo is_front_page() ? '' : ' is-sticky'; ?>">
	<div class="khas-header__inner">

		<a class="khas-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'خانه', 'khas' ); ?>">
			<div class="khas-logo__text">
				<b>KHAS</b>
				<i>خاص</i>
			</div>
			<img class="khas-logo__symbol" src="<?php echo esc_url( get_template_directory_uri() . '/assets/brand/khas-lifestyle-logo.png' ); ?>" alt="<?php esc_attr_e( 'خاص', 'khas' ); ?>" width="44" height="44" />
		</a>

		<nav class="khas-nav" aria-label="<?php esc_attr_e( 'منوی اصلی', 'khas' ); ?>">
			<a class="home-link<?php echo is_front_page() ? ' active' : ''; ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'خانه', 'khas' ); ?></a>
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'items_wrap'     => '%3$s',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
			} else {
				$khas_top_cats = get_categories(
					array(
						'orderby'    => 'count',
						'order'      => 'DESC',
						'number'     => 8,
						'hide_empty' => true,
					)
				);
				if ( $khas_top_cats ) {
					foreach ( $khas_top_cats as $kc ) {
						echo '<a href="' . esc_url( get_category_link( $kc->term_id ) ) . '">' . esc_html( $kc->name ) . '</a>';
					}
				}
			}
			?>
		</nav>

		<div class="khas-header__left">
			<a class="khas-btn khas-btn--gold header-cta" href="<?php echo esc_url( khas_membership_url() ); ?>"><?php esc_html_e( 'عضویت ویژه', 'khas' ); ?></a>
			<button class="khas-iconbtn" aria-label="<?php esc_attr_e( 'جستجو', 'khas' ); ?>" type="button">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="6.5"/><path d="m20 20-3.6-3.6"/></svg>
			</button>
			<button class="khas-iconbtn khas-burger" aria-label="<?php esc_attr_e( 'منو', 'khas' ); ?>" type="button">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
			</button>
		</div>
	</div>

	<div class="khas-mobile-nav">
		<ul>
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'خانه', 'khas' ); ?></a></li>
			<?php
			$mcats = get_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 9, 'hide_empty' => true ) );
			if ( $mcats ) {
				foreach ( $mcats as $mc ) {
					echo '<li><a href="' . esc_url( get_category_link( $mc->term_id ) ) . '">' . esc_html( $mc->name ) . '</a></li>';
				}
			}
			?>
		</ul>
	</div>
</header>
