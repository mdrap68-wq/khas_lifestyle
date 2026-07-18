# KHAS Lifestyle — KNOWN_ISSUES.md

Version: 1.0
Status: Active
Priority: High

---

# Purpose

This document tracks every known issue, limitation, unfinished feature, and technical concern in the KHAS Lifestyle project.

Before starting any development task, every AI agent and developer should review this file.

Completed issues should be moved to CHANGELOG.md after resolution.

---

# Critical Issues

## KI-001 — Payment Gateway Is Still Simulated

Status

Open

Priority

Critical

Description

The membership payment page currently uses a simulated payment flow.

No real bank gateway is connected.

Impact

Premium memberships cannot be sold in production.

Required Solution

Integrate a real Iranian payment gateway.

Examples:

- ZarinPal
- IDPay
- NextPay
- Mellat
- Saman
- Pasargad

---

## KI-002 — Premium Content Is Not Locked

Status

Open

Priority

Critical

Description

Membership plans exist.

Payment page exists.

However, premium content is still publicly accessible.

Required Solution

Implement access control.

---

## KI-003 — Membership Validation

Status

Open

Priority

Critical

Description

Users are not actually validated after purchase.

Membership expiration is not checked.

Required Solution

User role system

Membership expiration

Access middleware

---

# High Priority Issues

## KI-004 — SEO Review

Status

Open

Priority

High

Tasks

- Meta Description
- Open Graph
- Twitter Cards
- Canonical URLs
- Structured Data
- XML Sitemap
- Robots

---

## KI-005 — Performance Optimization

Status

Open

Priority

High

Tasks

- Image Optimization
- Lazy Loading
- Query Optimization
- Asset Minification
- Cache Review

---

## KI-006 — Accessibility Review

Status

Open

Priority

High

Tasks

- Keyboard Navigation
- Screen Reader
- Focus States
- ARIA Labels
- Color Contrast

---

# Medium Priority Issues

## KI-007 — Reading Progress

Status

Partially Implemented

Needs review.

---

## KI-008 — Newsletter Workflow

Status

Implemented

Needs real email provider.

Examples

- MailerLite
- Brevo
- Mailchimp

---

## KI-009 — Search Improvements

Status

Open

Current search is basic.

Future improvements

- Better relevance
- Suggestions
- Live search

---

## KI-010 — Comment System

Status

Implemented

Needs improvements

- Spam protection
- Better moderation
- Better styling

---

# UI Improvements

Pending

- Better empty states
- Better loading skeletons
- Better error pages
- Better animations
- Consistent spacing review

---

# Documentation Issues

Current documentation is complete.

Future updates required only when:

- Architecture changes
- Folder structure changes
- Major features added
- Business rules change

---

# Technical Debt

Current Technical Debt

LOW

Remaining debt mostly consists of:

- Minor refactoring
- Code cleanup
- Optimization

No major architectural rewrite is currently required.

---

# Future Enhancements

Possible future features

- User Dashboard
- Reading History
- Favorites
- Bookmarks
- Push Notifications
- Mobile App
- AI Content Recommendations
- Dark Mode
- Offline Reading

These are enhancements, not current requirements.

---

# Testing Status

Needs additional testing for:

- Membership flow
- Payment flow
- Responsive layouts
- RTL consistency
- Browser compatibility
- Accessibility

---

# Production Checklist

Before deployment verify:

✓ Security

✓ Performance

✓ SEO

✓ Accessibility

✓ Membership

✓ Payment

✓ Documentation

✓ Backups

✓ Error Handling

---

# Issue Workflow

Every issue should move through:

Open

↓

In Progress

↓

Testing

↓

Resolved

↓

Documented in CHANGELOG

Never remove an issue without documenting its resolution.

---

# Final Rule

This file should always reflect the real state of the project.

Never hide problems.

Document them clearly so future developers know exactly what remains to be done.