# KHAS Lifestyle — WORDPRESS_ARCHITECTURE.md
Version: 1.0
Status: Active
Owner: Project Manager
Priority: High

---

# Purpose

This document defines the technical architecture of the KHAS Lifestyle WordPress theme.

It explains how the project is organized, how components communicate, and how future development must be performed.

This document is the architectural reference for every developer and AI agent.

---

# Project Platform

CMS

WordPress

Theme Type

Custom Theme

Language

Persian (RTL)

Development Style

Classic WordPress Theme

---

# Theme Philosophy

The theme is intentionally developed without relying on page builders.

Reasons:

- Better Performance
- Cleaner Code
- Easier Maintenance
- Full Control
- Long-Term Stability

---

# Current Theme Structure

```
khas-theme/

assets/
│
├── brand/
├── css/
├── js/
├── images/

inc/

template-parts/

404.php

archive.php

category.php

comments.php

footer.php

front-page.php

functions.php

header.php

index.php

membership.php

page.php

payment-gateway.php

payment-result.php

searchform.php

single.php

style.css
```

---

# Directory Responsibilities

## assets/

Contains all frontend assets.

Includes:

Brand Assets

JavaScript

CSS

Images

Icons

Future Fonts

Never place PHP inside assets.

---

## assets/brand/

Contains all official project branding.

Examples:

Logo

Dark Logo

Light Logo

Favicons

Application Icons

Social Preview Images

Only official brand files belong here.

---

## inc/

Contains reusable PHP modules.

Examples:

Payment Logic

Helper Functions

API Logic

Future Modules

Business logic belongs here.

---

## template-parts/

Contains reusable frontend templates.

Examples:

Cards

Newsletter

Pagination

Author Box

Related Posts

Hero Blocks

Never duplicate HTML.

Reuse template parts.

---

# Root Theme Files

## functions.php

Project bootstrap.

Responsibilities:

Register menus

Register widgets

Register scripts

Register styles

Register theme supports

Load inc modules

Register hooks

Never place business logic directly here.

---

## front-page.php

Homepage template.

Responsible only for homepage rendering.

---

## single.php

Single article page.

Responsible for rendering articles.

---

## archive.php

Archive listing.

---

## category.php

Category pages.

---

## page.php

Static pages.

---

## comments.php

Comment rendering.

---

## header.php

Global Header.

---

## footer.php

Global Footer.

---

# Theme Loading Order

WordPress

↓

functions.php

↓

Theme Supports

↓

Assets

↓

Header

↓

Page Template

↓

Template Parts

↓

Footer

---

# Business Logic Separation

Business Logic

↓

inc/

Presentation

↓

Templates

Never mix these responsibilities.

---

# Payment Architecture

Current payment files:

membership.php

payment-gateway.php

payment-result.php

inc/payments.php

Future payment providers should integrate without changing template files.

Presentation should remain independent.

---

# Membership Architecture

Membership is a core subsystem.

Future architecture should support:

Multiple Plans

Premium Articles

Downloads

Courses

Member Dashboard

Subscriptions

Role-based Access

Membership rules should remain configurable.

---

# Asset Loading Rules

Use:

wp_enqueue_style()

wp_enqueue_script()

Never hardcode assets inside templates.

Always load through WordPress.

---

# Template Hierarchy

Homepage

↓

front-page.php

Posts

↓

single.php

Pages

↓

page.php

Categories

↓

category.php

Archives

↓

archive.php

Fallback

↓

index.php

---

# Hooks

Prefer:

Actions

Filters

WordPress APIs

Avoid modifying WordPress core.

---

# Database Philosophy

Use WordPress APIs whenever possible.

Avoid direct SQL.

Use:

WP_Query

get_post_meta

update_post_meta

Options API

Transients

User Meta

---

# Security

Every input must be:

Validated

Sanitized

Escaped

Every form must use:

Nonce

Capability Checks

Permission Validation

---

# Coding Standards

Follow:

WordPress Coding Standards

Readable PHP

Descriptive Names

Small Functions

Reusable Modules

---

# Scalability

The architecture must support:

New Content Types

New Payment Providers

REST API

Future Mobile App

Future AI Features

Without major rewrites.

---

# Documentation Rule

Whenever architecture changes:

Update:

WORDPRESS_ARCHITECTURE.md

PROJECT_MEMORY.md

CHANGELOG.md

Before implementation continues.

---

# Final Principle

Architecture should remain simple enough that a new developer can understand the project in a single day.