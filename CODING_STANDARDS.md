# KHAS Lifestyle — CODING_STANDARDS.md

Version: 1.0
Status: Permanent
Priority: Critical

---

# Purpose

This document defines the official coding standards for the KHAS Lifestyle project.

Every developer and every AI agent must follow these standards to keep the project consistent.

---

# General Principles

Code must always be:

- Simple
- Readable
- Maintainable
- Predictable
- Modular
- Documented

Readable code is always preferred over clever code.

---

# PHP Standards

Follow modern WordPress coding standards.

Always:

- Use strict comparisons when appropriate.
- Return early whenever possible.
- Keep functions focused on a single responsibility.
- Avoid nested conditionals.
- Prefer small reusable functions.

Avoid:

- Giant functions
- Repeated logic
- Magic numbers
- Unclear variable names

---

# WordPress Standards

Always prefer native WordPress APIs.

Use:

- WP_Query
- get_posts()
- get_option()
- update_option()
- wp_enqueue_script()
- wp_enqueue_style()
- add_action()
- add_filter()

Never recreate existing WordPress functionality.

---

# Naming Rules

Functions

snake_case

Example:

khas_render_membership_card()

Variables

Descriptive

Example:

$current_plan

$user_membership

$featured_articles

Files

Lowercase

Hyphen separated

Example:

membership-page.php

payment-gateway.php

newsletter-form.php

---

# Folder Standards

assets/

Only frontend assets.

inc/

Business logic.

template-parts/

Reusable template components.

Never mix responsibilities.

---

# CSS Standards

Mobile First

Reusable classes

No duplicated rules

Avoid:

!important

Deep selector chains

Inline styles

Prefer CSS variables whenever possible.

---

# JavaScript Standards

Modern JavaScript

Modular

No global variables

No unnecessary libraries

Load scripts only where needed.

---

# Security Standards

Always:

Validate

Sanitize

Escape

Nonce Protect

Permission Check

Never trust user input.

---

# Performance Standards

Every feature should minimize:

Database queries

JavaScript size

Image weight

DOM complexity

Network requests

---

# Responsive Standards

Every page must support:

Mobile

Tablet

Desktop

No desktop-only implementations.

---

# Accessibility Standards

Every interactive element requires:

Proper labels

Keyboard navigation

Visible focus

Semantic HTML

ARIA when necessary

---

# Documentation Standards

Any architectural change requires updating:

PROJECT_MEMORY

CURRENT_ARCHITECTURE

CHANGELOG

TASK_QUEUE

---

# Git Standards

Good Commit Messages

Add membership dashboard

Improve homepage performance

Fix payment validation

Bad Messages

update

fix

changes

test

---

# Review Checklist

Before every commit verify:

✓ No duplicated code

✓ No hardcoded business values

✓ Responsive

✓ Accessible

✓ Secure

✓ Documented

✓ Clean formatting

---

# Final Principle

Every new line of code should improve the maintainability of the project.