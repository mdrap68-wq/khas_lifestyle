# KHAS Lifestyle — UI_COMPONENT_LIBRARY.md

Version: 1.0
Status: Active
Priority: High

---

# Purpose

This document is the single source of truth for all UI components used in the KHAS Lifestyle project.

Before creating any new UI, every developer or AI agent must check this file.

If a suitable component already exists, it must be reused.

Never create duplicate components.

---

# Design Principles

Every component must follow the Design System.

Characteristics:

- Minimal
- Modern
- Premium
- Responsive
- Accessible
- RTL Compatible
- Consistent

---

# Layout Components

## Header

Status:
Implemented

Purpose:

Main navigation

Contains:

- Logo
- Navigation Menu
- Search
- Membership Button
- Mobile Menu

Rules:

- Fixed height
- Responsive
- Sticky on scroll
- Never overcrowded

---

## Footer

Status:
Implemented

Contains:

- Logo
- Copyright
- Social Links
- Newsletter
- Quick Links

Rules:

Keep minimal.

---

## Container

Purpose:

Maximum content width.

Rules:

Never hardcode page width.

Always use container.

---

## Section

Purpose:

Vertical spacing.

Rules:

Spacing must follow Design System.

---

# Navigation Components

## Main Menu

Status:
Implemented

Responsive.

Supports:

- Desktop
- Mobile

---

## Mobile Navigation

Status:
Implemented

Slide Menu

Must support:

- Touch
- Keyboard
- Close Button

---

## Breadcrumb

Status:
Planned

Purpose:

Improve navigation and SEO.

---

# Content Components

## Article Card

Status:
Implemented

Displays:

- Thumbnail
- Category
- Title
- Excerpt
- Read Time
- Date

Rules:

Cards should always have equal height.

---

## Featured Article

Status:
Implemented

Used only once per page.

Larger than normal cards.

---

## Category Card

Status:
Implemented

Used on category archive.

---

## Pagination

Status:
Implemented

Simple numeric navigation.

---

# Membership Components

## Membership Card

Status:
Implemented

Contains:

- Plan Name
- Price
- Features
- CTA Button

Rules:

Premium plan visually highlighted.

---

## Payment Form

Status:
Implemented

Current:

Demo Gateway

Future:

Real Gateway

---

## Payment Result

Status:
Implemented

Displays:

- Success
- Failure
- Pending

---

# Forms

## Search Form

Status:
Implemented

Simple single-field search.

---

## Newsletter Form

Status:
Implemented

Fields:

- Email

Future:

Optional Name

---

## Contact Form

Status:
Planned

---

# UI Elements

## Primary Button

Purpose:

Main actions.

Examples:

- Join Membership
- Read More
- Continue

---

## Secondary Button

Purpose:

Alternative actions.

---

## Ghost Button

Purpose:

Low emphasis actions.

---

## Text Link

Purpose:

Navigation.

---

# Cards

Current Card Types

- Article Card
- Membership Card
- Category Card

Future

- Product Card
- Author Card
- Course Card

---

# Typography Components

Current

- Page Title
- Section Title
- Card Title
- Body Text
- Caption

Never introduce new typography styles without updating DESIGN_SYSTEM.md.

---

# Status Components

Available

- Success
- Error
- Warning
- Info

Used for:

- Forms
- Payments
- Notifications

---

# Loading Components

Planned

- Skeleton Cards
- Loading Spinner
- Lazy Image Placeholder

---

# Empty State Components

Planned

Examples:

No Articles

No Search Results

No Favorites

---

# Error Components

404 Page

Implemented

500 Page

Planned

---

# Icons

Official Icons

Stored in:

assets/brand/

Do not replace brand icons.

---

# Images

Brand Assets

Location:

assets/brand/

Includes:

- Logo
- Dark Logo
- Light Logo
- Icons
- Favicons
- Social Preview

Always use official assets.

---

# Future Components

Potential future reusable components:

- Reading Progress Bar
- Share Buttons
- Author Box
- Related Articles
- Comments Section
- FAQ Accordion
- Premium Badge
- User Dashboard Widgets

---

# Component Rules

Before creating a component ask:

1. Does it already exist?

2. Can it be extended?

3. Can it become reusable?

If yes:

Reuse.

Never duplicate functionality.

---

# Final Principle

Every component should feel like it belongs to one unified design language.

Users should never notice differences between pages because every UI element should behave consistently.