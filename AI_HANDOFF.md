# KHAS Lifestyle — AI_HANDOFF.md
Version: 1.0
Status: Active
Audience: Any future AI agent working on the project

---

# Purpose

This file is the first document every AI agent must read before writing, modifying, or reviewing any code.

Its purpose is to transfer complete project context between AI sessions without relying on previous conversations.

The AI must assume that previous chat history is unavailable and use only the documentation files inside the project.

---

# Required Reading Order

Before performing **any task**, read these files in the following order:

1. PROJECT_MEMORY.md
2. PROJECT_RULES.md
3. DESIGN_SYSTEM.md
4. WORDPRESS_ARCHITECTURE.md
5. CURRENT_ARCHITECTURE.md
6. DEVELOPMENT_RULES.md
7. ROADMAP.md
8. TASK_QUEUE.md
9. CHANGELOG.md

Do not skip this order.

---

# Project Identity

Project Name

KHAS Lifestyle

Platform

WordPress Custom Theme

Language

Persian (RTL)

Architecture

Classic WordPress

No page builders

Minimal custom PHP

Responsive-first

---

# Primary Objective

Build a premium Persian lifestyle platform with:

- High performance
- Excellent user experience
- Clean architecture
- Maintainable code
- Long-term scalability

Every decision must support these goals.

---

# What You Must Never Do

Never redesign the visual identity without approval.

Never introduce heavy frameworks.

Never install unnecessary plugins.

Never duplicate functionality.

Never overwrite existing code without understanding it.

Never delete documentation.

Never ignore coding standards defined in PROJECT_RULES.md.

---

# Development Workflow

Before coding:

- Read documentation.
- Understand the task.
- Check TASK_QUEUE.md.
- Review CURRENT_ARCHITECTURE.md.
- Identify affected files.

During development:

- Make the smallest safe change.
- Preserve backward compatibility.
- Follow naming conventions.
- Keep code readable.

After development:

- Update CHANGELOG.md.
- Update TASK_QUEUE.md.
- Update PROJECT_MEMORY.md if project knowledge changes.
- Update CURRENT_ARCHITECTURE.md if architecture changes.

---

# Code Style Expectations

Every modification should be:

- Simple
- Readable
- Modular
- Predictable
- Documented

Avoid clever code.

Prefer maintainable code.

---

# Design Expectations

Respect the Design System.

Do not invent new colors.

Do not invent new spacing values.

Do not invent new typography.

Everything must follow DESIGN_SYSTEM.md.

---

# WordPress Expectations

Always use WordPress APIs.

Use:

esc_html()

esc_attr()

wp_nonce_field()

check_admin_referer()

wp_enqueue_script()

wp_enqueue_style()

sanitize_text_field()

wp_kses_post()

Never bypass WordPress security.

---

# Security Rules

Assume all user input is unsafe.

Validate everything.

Escape all output.

Never trust POST or GET values.

Never expose secrets.

---

# Performance Rules

Prefer server-side rendering.

Avoid unnecessary JavaScript.

Minimize database queries.

Lazy load heavy resources.

Optimize images.

---

# Documentation Rules

If behavior changes:

Update documentation immediately.

Documentation is part of the codebase.

It is never optional.

---

# Communication Style

When reporting work:

Explain:

- What changed
- Why it changed
- Risks
- Files modified
- Next recommended task

Keep reports technical and concise.

---

# If Information Is Missing

Never invent project facts.

Instead:

- Mark as unknown.
- Ask the Project Manager.
- Add a TODO if appropriate.

---

# Final Instruction

Every future AI agent must leave the project in a better state than it was found.

Code quality, documentation quality, and architectural consistency are equally important.