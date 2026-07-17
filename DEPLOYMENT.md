# Deployment Guide — khas-theme

This theme has no build step and no server-side runtime beyond standard WordPress + PHP. Deployment is the same as any WordPress theme.

## Requirements in Production

- A WordPress hosting environment (managed WordPress hosting, shared hosting, VPS, or self-managed server)
- PHP 7.4+
- HTTPS strongly recommended (required for the Web Share / Instagram links to behave correctly in modern browsers)

## Deployment Steps

### Option A — Managed WordPress Hosting (WP Engine, Kinsta, Bluehost, SiteGround, etc.)

1. Package the repository as a zip (see `INSTALLATION.md` step 1).
2. Upload via **Appearance → Themes → Add New Theme → Upload Theme** in `wp-admin`.
3. Activate the theme.
4. Run the demo content installer (optional, recommended for a fresh site) or configure content manually.
5. Set your production `DATABASE_URL`-equivalent — WordPress handles this via `wp-config.php`, managed automatically by most hosts.

### Option B — Self-Managed Server (VPS / Docker)

1. Ensure a working WordPress install is deployed (standard `wordpress` Docker image or manual LAMP/LEMP stack).
2. Copy this repository into `wp-content/themes/khas-theme/` on the server:
   ```bash
   scp -r ./khas-theme user@yourserver:/var/www/html/wp-content/themes/
   ```
3. Activate via `wp-admin` or WP-CLI:
   ```bash
   wp theme activate khas-theme
   ```
4. Run demo content installation via `wp-admin`, or programmatically via WP-CLI if you script the equivalent `khas_install_demo_content()` call.

### Option C — WP-CLI (scripted / CI deployment)

```bash
wp theme install /path/to/khas-theme.zip --activate
```

## Assets

The official KHAS brand logo lives at `assets/brand/khas-lifestyle-logo.png` inside this repository and is the single source of truth for:
- The header logo
- The footer logo
- The site favicon / apple-touch-icon
- The auto-configured Custom Logo (Appearance → Customize → Site Identity)

No other logo file should be introduced — if the brand asset changes, replace this one file and every reference updates automatically (all theme templates reference this single path via `get_template_directory_uri()`).

## Post-Deploy Verification Checklist

- [ ] Homepage loads and displays the hero, featured articles, and footer correctly
- [ ] Site icon / favicon shows the KHAS logo in the browser tab
- [ ] Newsletter form on the homepage submits successfully (check **خبرنامه خاص** in admin)
- [ ] Category pages and single article pages render without PHP notices/warnings
- [ ] Social links in the footer point to the correct URLs (configured via Customizer)
