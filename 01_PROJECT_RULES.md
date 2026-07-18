# KHAS Lifestyle — PROJECT_RULES.md
Version: 1.0
Status: Permanent
Priority: Highest

---

# Purpose

This document defines the permanent development rules of KHAS Lifestyle.

Every AI agent, developer, contributor, or automation tool MUST follow these rules before writing or modifying any code.

These rules override personal preferences.

---

# RULE 1 — Never Guess

If information is unknown:

- Ask.
- Mark it as Pending.
- Never invent values.

Examples:

❌ Brand color

❌ Typography

❌ Business rules

❌ Payment logic

❌ SEO policy

---

# RULE 2 — Read Documentation First

Before implementing anything, always read:

00_PROJECT_MEMORY.md

01_PROJECT_RULES.md

02_DESIGN_SYSTEM.md

03_WORDPRESS_ARCHITECTURE.md

04_ROADMAP.md

05_TASK_QUEUE.md

06_CHANGELOG.md

07_AI_HANDOFF.md

08_CURRENT_ARCHITECTURE.md

09_DEVELOPMENT_RULES.md

10_NEXT_AGENT_INSTRUCTIONS.md

11_PROJECT_CONTEXT.md

No implementation starts before reading them.

---

# RULE 3 — Simplicity Wins

Always choose:

Simple

Readable

Maintainable

Predictable

Avoid:

Over-engineering

Fancy abstractions

Unnecessary patterns

Deep inheritance

Complex dependency graphs

---

# RULE 4 — Performance First

Every feature must consider:

Loading speed

Rendering speed

Database performance

Caching opportunities

Minimal JavaScript

Minimal CSS

Minimal HTTP Requests

---

# RULE 5 — WordPress Native First

Prefer WordPress APIs over custom implementations.

Examples:

WP_Query

Template Hierarchy

Hooks

Filters

Actions

Theme Supports

Customizer

Menus

Widgets

Do not reinvent WordPress.

---

# RULE 6 — Plugin Minimalism

Plugins are expensive.

Never add a plugin unless:

It solves a real problem.

It cannot reasonably be implemented inside the theme.

It is actively maintained.

It has long-term value.

---

# RULE 7 — Modular Architecture

Every feature should live in its own logical module.

Business logic must not be mixed with presentation.

Avoid giant files.

Avoid giant functions.

Avoid copy-paste code.

---

# RULE 8 — Naming Standards

Names must be descriptive.

Good:

membership_plan_price

featured_articles

newsletter_form

Bad:

data

temp

test

value

---

# RULE 9 — No Magic Numbers

Avoid:

hardcoded IDs

hardcoded URLs

hardcoded prices

hardcoded colors

hardcoded category names

Use constants or configuration whenever possible.

---

# RULE 10 — No Silent Changes

AI must never silently change:

Architecture

Folder Structure

Business Rules

Visual Identity

User Experience

Major Features

Without explicit approval.

---

# RULE 11 — Every File Has One Responsibility

Each file should have one clear purpose.

If a file becomes too large:

Split it.

Do not create "God Files."

---

# RULE 12 — Security

Always validate:

Input

Output

Nonce

Permissions

Escaping

Sanitization

Prepared SQL

Never trust user input.

---

# RULE 13 — Database

Avoid unnecessary database queries.

Cache when appropriate.

Never query inside loops if avoidable.

Optimize before adding complexity.

---

# RULE 14 — CSS Rules

No inline CSS unless absolutely necessary.

No duplicated styles.

Prefer reusable utility classes.

Avoid !important unless unavoidable.

---

# RULE 15 — JavaScript Rules

Keep JavaScript modular.

Avoid global variables.

Avoid unnecessary dependencies.

Progressive enhancement whenever possible.

---

# RULE 16 — Accessibility

Always consider:

Keyboard navigation

Semantic HTML

Labels

ARIA when necessary

Contrast

Readable typography

Accessibility is not optional.

---

# RULE 17 — Responsive Design

Mobile First

Tablet Second

Desktop Third

Never design desktop-only components.

---

# RULE 18 — Documentation

Every significant architectural change requires updating:

PROJECT_MEMORY

CHANGELOG

ROADMAP

Relevant documentation

Documentation is mandatory.

---

# RULE 19 — Git Rules

Commit messages should be meaningful.

Examples:

Add membership dashboard

Improve payment validation

Fix responsive navigation

Avoid:

update

fix

changes

test

---

# RULE 20 — AI Behaviour

AI acts as:

Senior Software Engineer

Senior Architect

Senior WordPress Developer

Not as a beginner.

Always explain important architectural decisions.

Never produce unnecessary code.

---

# RULE 21 — Quality Over Speed

Fast code generation is never more important than:

Correctness

Maintainability

Security

Consistency

---

# RULE 22 — Business Logic

Business logic must remain configurable.

Avoid hardcoding:

Plan names

Prices

Discounts

Membership rules

Gateway settings

---

# RULE 23 — User Experience

Every interaction should feel:

Fast

Clear

Minimal

Predictable

Elegant

No unnecessary popups.

No unnecessary animations.

---

# RULE 24 — Backward Compatibility

Do not break existing functionality.

Every new feature must integrate safely.

Prefer extension over replacement.

---

# RULE 25 — Final Principle

Whenever two solutions are possible:

Choose the one that future developers will understand six months later.

Readable code always beats clever code.