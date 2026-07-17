# Dependency List — khas-theme

This theme intentionally has **zero npm/Node.js dependencies** and **zero Composer dependencies**. It is built entirely with:

## Platform Requirements

| Requirement | Version | Purpose |
|---|---|---|
| WordPress | 6.0+ | Core CMS platform this theme runs on |
| PHP | 7.4+ | Server-side language for all `.php` template files |
| MySQL / MariaDB | Whatever the WordPress install uses | Storage for posts, categories, options, and the custom `wp_khas_subscribers` table |

## Bundled Assets (no package manager involved)

| Asset | Location | Purpose |
|---|---|---|
| KHAS brand logo | `assets/brand/khas-lifestyle-logo.png` | Single source of truth for header, footer, favicon, and Customizer logo |
| Theme styles | `style.css` | All CSS for the theme (design tokens, layout, components) — also serves as the required WordPress theme header file |
| Mobile menu + newsletter AJAX | `assets/main.js` | Vanilla JavaScript, no framework |
| Reading progress + fade-in | `assets/reading-progress.js` | Vanilla JavaScript, no framework |

## External Services

| Service | Purpose |
|---|---|
| Google Fonts (`Vazirmatn`) | Loaded via `<link>` in `functions.php` — same typeface as the `khas-pwa` Next.js app, for brand consistency |

## WordPress Core APIs Used

- Customizer API (`customize_register`) — social media links section
- Theme Modification API (`get_theme_mod` / `set_theme_mod`) — custom logo, social URLs, nav menu locations
- `$wpdb` — custom `wp_khas_subscribers` table for newsletter signups
- WP-Cron-free AJAX handlers (`wp_ajax_*` / `wp_ajax_nopriv_*`) — newsletter subscription, demo content installer
- Post Thumbnails, Custom Logo, Nav Menus, HTML5 theme supports

## No Dependency On

- Node.js, npm, or any JavaScript build tooling
- Composer or any PHP package
- The `khas-pwa` Next.js application — fully independent, no shared code or runtime
