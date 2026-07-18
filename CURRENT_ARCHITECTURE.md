# KHAS Lifestyle — CURRENT_ARCHITECTURE.md
Version: 1.0
Status: Active

---

# Purpose

This document describes the **current implementation** of the KHAS Lifestyle project.

Unlike the Roadmap, this file must always reflect the architecture that actually exists today.

Whenever the codebase changes structurally, this document must be updated.

---

# Project Overview

Project Name

KHAS Lifestyle

Platform

WordPress

Implementation

Custom Theme

Language

Persian (RTL)

Architecture

Classic WordPress Theme

Git Repository

GitHub (Private)

---

# Current Theme Structure

```
khas-theme/
│
├── assets/
│   ├── brand/
│   ├── main.js
│   ├── payment.js
│   └── reading-progress.js
│
├── inc/
│   ├── demo.php
│   └── payments.php
│
├── template-parts/
│   ├── newsletter.php
│   └── pagination.php
│
├── 404.php
├── archive.php
├── category.php
├── comments.php
├── footer.php
├── front-page.php
├── functions.php
├── header.php
├── index.php
├── membership.php
├── page.php
├── payment-gateway.php
├── payment-result.php
├── searchform.php
├── single.php
├── style.css
│
└── Documentation Files
```

---

# Core Templates

## Homepage

front-page.php

Status

Implemented

---

## Single Post

single.php

Status

Implemented

---

## Category Archive

category.php

Status

Implemented

---

## Archive

archive.php

Status

Implemented

---

## Static Page

page.php

Status

Implemented

---

## 404

404.php

Status

Implemented

---

# Core Includes

## functions.php

Responsibilities

- Theme setup
- Assets loading
- Theme supports
- Menus
- Widgets
- Payment integration
- Utilities

Status

Implemented

---

## header.php

Responsibilities

- Navigation
- Logo
- Search
- Mobile menu

Status

Implemented

---

## footer.php

Responsibilities

- Footer
- Newsletter
- Copyright
- Social Links

Status

Implemented

---

# Assets

## JavaScript

main.js

General interactions

payment.js

Membership payment logic

reading-progress.js

Reading progress indicator

---

## Brand Assets

Location

assets/brand/

Includes

- Logo
- SVG logo
- Favicon
- Icons
- Social preview
- Dark logo
- Light logo

Status

Complete

---

# Membership System

Current Files

membership.php

payment-gateway.php

payment-result.php

inc/payments.php

Status

Implemented (Demo)

Current State

Gateway is simulated.

Real payment provider has not yet been integrated.

---

# Newsletter

Current Component

template-parts/newsletter.php

Status

Implemented

---

# Pagination

Current Component

template-parts/pagination.php

Status

Implemented

---

# Theme Features

Currently Available

✔ Homepage

✔ Articles

✔ Categories

✔ Search Form

✔ Membership Page

✔ Payment Simulation

✔ Newsletter

✔ Responsive Layout

✔ Reading Progress

✔ Brand Assets

---

# Missing Features

Still Planned

- Premium content locking
- User dashboard
- Bookmark system
- Notifications
- Related articles
- Breadcrumbs
- Dark mode
- AI recommendation engine
- Advanced search
- Analytics dashboard

---

# Current Development Status

Foundation

✅ Complete

Homepage

🟡 In Progress

Membership

🟡 In Progress

Payment

🟡 Demo Version

SEO

⬜ Pending

Performance

⬜ Pending

Accessibility

⬜ Pending

Testing

⬜ Pending

Deployment

⬜ Pending

---

# Known Technical Debt

Current Issues

- Payment gateway is demonstration only.
- Premium access is not yet enforced.
- SEO review pending.
- Accessibility audit pending.
- Performance optimization pending.

---

# Architecture Rules

The project currently follows:

- Classic WordPress architecture
- No page builders
- Minimal JavaScript
- PHP-first rendering
- Modular template parts
- Reusable components
- Documentation-driven development

---

# Update Policy

Whenever:

- New file added
- File removed
- Folder structure changes
- Architecture changes
- New subsystem added

This document must be updated immediately.

---

# Final Rule

CURRENT_ARCHITECTURE.md must always describe the project **exactly as it exists today**, never as it is planned to become.