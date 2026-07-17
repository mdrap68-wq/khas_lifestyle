# Installation Guide — khas-theme

## Prerequisites

| Requirement | Version |
|---|---|
| WordPress | 6.0 or later |
| PHP | 7.4 or later |
| MySQL / MariaDB | Whatever your WordPress install already uses |

No Node.js, npm, or build step is required — this is a plain PHP + CSS + vanilla JS theme.

## 1. Package the theme

From the repository root:

```bash
zip -r -X khas-theme.zip . -x "*.DS_Store" -x "*.git*"
```

(Or simply zip the entire repository folder — WordPress only needs a folder containing `style.css` with a valid theme header at its root.)

## 2. Install via WordPress Admin

1. Log in to `wp-admin`.
2. Go to **Appearance → Themes → Add New Theme → Upload Theme**.
3. Choose `khas-theme.zip` and click **Install Now**.
4. Click **Activate**.

## 3. Install demo content (recommended for first run)

After activation, an admin notice appears: **"قالب خاص فعال شد 🎉"**. Click through to install demo content, or go manually to:

**Appearance → محتوای دمو (Demo Content) → نصب محتوای دمو (Install Demo Content)**

This automatically creates:
- 8 categories with icons
- 11 sample articles with featured images
- A primary navigation menu
- A "Home" page set as the front page
- One sticky post for the hero section
- The official KHAS logo set as both the Custom Logo and Site Icon (only if not already configured)

## 4. Configure Social Links (optional)

Go to **Appearance → Customize → شبکه‌های اجتماعی (Social Networks)** to set your Instagram, Telegram, Twitter, and Pinterest URLs. These populate the footer, the Instagram grid, and author cards.

## 5. Verify Newsletter Functionality

The newsletter form on the homepage stores subscribers in a custom table (`wp_khas_subscribers`), created automatically on theme activation. View subscribers under **خبرنامه خاص (KHAS Newsletter)** in the admin sidebar.

## Local Development Environment

If you want to develop against this theme locally, use any standard local WordPress environment:

- [LocalWP](https://localwp.com/)
- [DevKinsta](https://kinsta.com/devkinsta/)
- Docker (`wordpress` + `mysql` official images)

Place this repository's contents inside `wp-content/themes/khas-theme/` of your local WordPress install, then activate it from `wp-admin` as described above.
