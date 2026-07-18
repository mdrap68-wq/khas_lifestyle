# KHAS Lifestyle — NEXT_AGENT_INSTRUCTIONS.md
Version: 1.0
Status: Active
Priority: Critical

---

# Purpose

This document tells every future AI agent exactly how to continue working on KHAS Lifestyle.

The goal is that a new AI can continue the project with zero prior conversation.

---

# Before Doing Anything

Read these files in order:

1. PROJECT_MEMORY.md
2. PROJECT_RULES.md
3. DESIGN_SYSTEM.md
4. WORDPRESS_ARCHITECTURE.md
5. CURRENT_ARCHITECTURE.md
6. DEVELOPMENT_RULES.md
7. ROADMAP.md
8. TASK_QUEUE.md
9. CHANGELOG.md

Only after reading them may development begin.

---

# Project Summary

Project

KHAS Lifestyle

Platform

WordPress Custom Theme

Language

Persian (RTL)

Architecture

Classic WordPress

Repository

GitHub (Private)

Theme Folder

khas-theme

---

# Current Development Phase

The project is currently focused on building a stable, production-ready WordPress theme.

The priority is NOT adding features.

The priority is:

- Stability
- Code Quality
- Performance
- Security
- Documentation

---

# Current Priorities

Highest Priority

1. Homepage refinement
2. Membership system completion
3. Real payment integration
4. SEO optimization
5. Performance optimization
6. Accessibility review
7. Final QA

---

# Things Already Available

The project already contains:

- Homepage
- Header
- Footer
- Category pages
- Archive pages
- Single article template
- Membership page
- Payment simulation
- Newsletter
- Brand assets
- Documentation system

Do not rebuild existing systems.

Improve them.

---

# Documentation Policy

Whenever you change:

Architecture

Folder structure

Business logic

Major UX

Theme organization

Update documentation immediately.

Documentation is part of development.

---

# Code Review Checklist

Before finishing any task verify:

✓ Responsive

✓ Secure

✓ Readable

✓ Modular

✓ WordPress Standard

✓ No duplicate logic

✓ No unnecessary dependencies

✓ Documentation updated

---

# WordPress Rules

Always prefer:

WordPress APIs

Hooks

Filters

Actions

Theme Supports

Customizer

Options API

Transients

Avoid custom solutions when WordPress already provides one.

---

# Security Rules

Every user input must be:

Validated

Sanitized

Escaped

Nonce protected

Never expose sensitive information.

---

# Performance Rules

Prefer:

Server-side rendering

Minimal JavaScript

Optimized images

Cached queries

Reusable components

Fast loading

---

# Design Rules

Never invent:

Colors

Fonts

Spacing system

Visual identity

Everything must follow DESIGN_SYSTEM.md.

---

# Git Rules

Every meaningful change requires:

Meaningful commit message

Updated CHANGELOG

Updated TASK_QUEUE

If architecture changed:

Update CURRENT_ARCHITECTURE

---

# Communication Rules

When reporting completed work always include:

Files modified

Reason for change

Possible risks

Suggested next task

Keep reports technical and concise.

---

# If Something Is Unknown

Never guess.

Instead:

Mark as Pending

Ask the PM

Document assumptions separately

---

# Things To Avoid

Do NOT:

Rewrite working code

Introduce unnecessary frameworks

Install unnecessary plugins

Break backward compatibility

Ignore documentation

Skip testing

---

# Future Development Order

1. Foundation
2. UX
3. Performance
4. SEO
5. Premium Features
6. Mobile
7. AI Features

Always preserve this order unless instructed otherwise.

---

# Final Instruction

Leave the project cleaner than you found it.

If a future developer opens this repository six months from now, they should immediately understand:

- How the project works.
- Why decisions were made.
- What should be done next.