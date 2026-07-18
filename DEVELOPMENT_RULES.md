# KHAS Lifestyle — DEVELOPMENT_RULES.md
Version: 1.0
Status: Permanent
Priority: Critical

---

# Purpose

This document defines the coding standards and engineering rules for KHAS Lifestyle.

Every contributor must follow these rules before writing code.

These rules are mandatory.

---

# 1. General Philosophy

Code must always be:

- Readable
- Predictable
- Maintainable
- Modular
- Scalable

Readable code is always preferred over clever code.

---

# 2. WordPress First

Always use native WordPress APIs before creating custom implementations.

Preferred APIs include:

- WP_Query
- get_posts()
- get_post_meta()
- update_post_meta()
- get_option()
- update_option()
- wp_enqueue_script()
- wp_enqueue_style()

Never reinvent existing WordPress functionality.

---

# 3. File Responsibility

One file = One responsibility.

Never create "God files."

Large files should be split into logical modules.

---

# 4. Function Rules

Functions should:

- Have one responsibility
- Be short
- Have descriptive names
- Avoid side effects
- Return predictable values

Avoid functions longer than approximately 80–100 lines unless absolutely necessary.

---

# 5. Naming Conventions

Use descriptive English names.

Good:

```php
get_membership_price()
render_featured_articles()
save_newsletter_subscription()
```

Bad:

```php
data()
run()
save()
test()
```

---

# 6. Folder Rules

Business Logic

→ inc/

Templates

→ template-parts/

Assets

→ assets/

Brand Files

→ assets/brand/

Never mix responsibilities.

---

# 7. Security

Always:

Validate

Sanitize

Escape

Use:

sanitize_text_field()

sanitize_email()

esc_html()

esc_attr()

wp_nonce_field()

check_admin_referer()

Never trust user input.

---

# 8. Database

Avoid unnecessary queries.

Never query inside loops when avoidable.

Prefer caching.

Prefer WordPress APIs.

---

# 9. CSS Rules

CSS must be:

Reusable

Organized

Minimal

Avoid:

Huge selectors

!important

Deep nesting

Duplicated styles

---

# 10. JavaScript Rules

JavaScript should:

Remain modular

Avoid globals

Avoid unnecessary dependencies

Use progressive enhancement

Only load scripts where required.

---

# 11. Responsive Rules

Mobile First

Tablet

Desktop

Every feature must be responsive from the beginning.

---

# 12. Accessibility

Every feature must support:

Keyboard navigation

Screen readers

Proper labels

Semantic HTML

Visible focus states

---

# 13. Performance

Every new feature should consider:

Rendering cost

Network cost

JavaScript size

Database load

Image optimization

Performance is a feature.

---

# 14. Documentation

Whenever architecture changes:

Update:

PROJECT_MEMORY.md

CURRENT_ARCHITECTURE.md

CHANGELOG.md

Documentation is mandatory.

---

# 15. Git Commit Rules

Good commit messages:

Add membership dashboard

Improve homepage performance

Fix payment validation

Bad commit messages:

update

fix

changes

test

---

# 16. Refactoring Rules

Never refactor working code only because "it looks better."

Refactor only when:

Improving maintainability

Reducing duplication

Improving performance

Fixing architecture

---

# 17. Error Handling

Errors should:

Be predictable

Be logged

Provide useful information

Never expose sensitive information.

---

# 18. Business Logic

Business logic must never be hardcoded.

Keep configurable:

Membership plans

Prices

Gateway settings

Limits

Permissions

---

# 19. AI Rules

AI must:

Understand before modifying.

Explain important decisions.

Avoid unnecessary rewrites.

Preserve existing functionality.

---

# 20. Quality Checklist

Before considering any task complete:

✓ Code works

✓ Responsive

✓ Secure

✓ Documented

✓ Readable

✓ No duplication

✓ No unnecessary complexity

✓ PM approval received

---

# Final Principle

Every line of code should make the project easier to maintain—not harder.