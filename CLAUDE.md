# CLAUDE.md — Fluid Font Forge (FFF)

> Read parent CLAUDE.md files first — in order:
> 1. `E:/projects/CLAUDE.md` — global conventions, git workflow, backups, CSS debugging
> 2. `E:/projects/plugins/CLAUDE.md` — WordPress plugin architecture, PHP/JS debugging, release workflow
>
> This file covers FFF-specific rules only.

---

## Plugin Identity

- **Plugin name:** Fluid Font Forge
- **Acronym / folder:** `fff` → `E:\projects\plugins\fff`
- **Version:** v5.3.0
- **GitHub:** https://github.com/Mij-Strebor/fluid-font-forge
- **Text domain:** `fluid-font-forge`
- **Admin page slug:** `fluid-font-forge`
- **Required capability:** `manage_options`
- **Namespace:** `JimRWeb\FluidFontForge` (legacy — do not rename without a migration plan)
- **Branding:** Always "Jim R Forge" — never "JimRWeb" or "JimRForge"
- **Author URI:** https://jimrforge.com

---

## What FFF Does

FFF is a WordPress admin tool that generates CSS `clamp()` values for fluid (responsive) typography. It provides:
- A data table of type-scale entries (min size, max size, viewport range)
- Multiple output formats: CSS classes, CSS custom properties, Tailwind utilities, HTML tag declarations
- Real-time preview
- Import/export of settings

---

## REPO HEALTH — CRITICAL

FFF's working tree has disappeared twice (2025-10-31, 2026-02-28). Check at every session start:

```bash
ls E:/projects/plugins/fff/.git/       # Must show HEAD, config, refs/ — not just objects/
git -C E:/projects/plugins/fff status  # Must return a branch name
```

Signs of a broken repo: `.git/` contains only `objects/` — no HEAD, config, or refs.

Recovery:
```bash
cd E:/projects/plugins
mv fff fff-broken-backup
git clone https://github.com/Mij-Strebor/fluid-font-forge fff
```

---

## CSS / JS Prefix

All CSS classes, AJAX action names, and option keys use the `fff-` / `fff_` prefix.

| Layer | Prefix | Example |
|-------|--------|---------|
| CSS classes | `fff-` | `fff-btn`, `fff-modal` |
| AJAX actions | `fff_` | `fff_save_settings` |
| WP option keys | `fff_` | `fff_settings` |
| JS namespace | `FFF` (if used) | |

---

## FFF-Specific Rules

- **Nonce constant:** `NONCE_ACTION` — defined once in the main class; never duplicated as an inline string.
- **Data persistence:** WP Options table only — no custom database tables.
- **No build process:** Pure PHP/JS/CSS. Hard-refresh (Ctrl+Shift+R) after JS/CSS edits.
- **Never use `vh` or `vw` units in CSS.** Viewport-relative units render unpredictably across different screen sizes, admin sidebar widths, and browser zoom levels. Use `px` for fixed layouts and `%` for fluid ones. Root cause: `50vh` in forge-header.css broke layout on production sites with shorter viewports (fixed in v5.3.3).

---

## JimRForge UI Standards — FFF Implementation

FSF v1.2.4 is the canonical UI prototype. Match it exactly for all visual work.
Full reference: `E:\projects\JIMRFORGE-UI-STANDARDS.md`

### Brand

- Organization name: **Jim R Forge** — not "JimRWeb", not "JimRForge"
- Author URI: https://jimrforge.com

### Color System — Exact Values

Never substitute, approximate, or invent color values. Copy exactly:

```css
:root {
    /* Browns */
    --clr-primary:    #3d2f1f;    /* Deep brown — headings, button text */
    --clr-secondary:  #6d4c2f;    /* Medium brown — body text */
    --clr-tertiary:   #86400e;    /* Accent brown */

    /* Gold */
    --clr-accent:     #f4c542;    /* Gold — buttons, highlights */
    --clr-btn-hover:  #dda824;    /* Gold hover (15–20% darker) */

    /* Backgrounds (4-level hierarchy) */
    --clr-age-bg:     #faf6f0;    /* Level 1: page background */
    --clr-card-bg:    #ffffff;    /* Level 2: card/container */
    --clr-light:      #faf9f6;    /* Level 3: panel background */
    --clr-white:      #fff;       /* Level 4: form field */

    /* Text */
    --clr-txt:        #6d4c2f;
    --clr-txt-light:  #faf9f6;
    --clr-txt-muted:  #64748b;

    /* Links */
    --clr-link:       #ce6565;
    --clr-link-hover: #b54545;

    /* Borders & shadows */
    --clr-border:     #c9b89a;
    --clr-shadow-sm:  0 1px 2px rgba(61, 47, 31, 0.08);
    --clr-shadow-md:  0 4px 6px rgba(61, 47, 31, 0.12);
    --clr-shadow-lg:  0 10px 20px rgba(61, 47, 31, 0.15);
    --clr-shadow-xl:  0 20px 30px rgba(61, 47, 31, 0.18);

    /* Status */
    --clr-success:    #059669;    --clr-success-bg: #ecfdf5;
    --clr-error:      #dc2626;    --clr-error-bg:   #fee2e2;
    --clr-warning:    #f59e0b;    --clr-warning-bg: #fef3c7;
    --clr-info:       #3b82f6;    --clr-info-bg:    #dbeafe;
}
```

### Typography

- **Font:** Inter (locally loaded, WOFF2)
- **Required files:** `assets/fonts/Inter-Regular.woff2`, `Inter-Medium.woff2`, `Inter-SemiBold.woff2`, `Inter-Bold.woff2`
- **Base size:** 16px
- **Weights:** 400, 500, 600, 700

```css
--fs-xxs: 11px;   --fs-xs: 13px;   --fs-sm: 14px;   --fs-md: 16px;
--fs-lg:  18px;   --fs-xl: 20px;   --fs-xxl: 24px;  --fs-xxxl: 32px;
```

Font-face declaration required for each weight (`font-display: swap`). Apply globally within the plugin scope with `font-family: var(--fs-body) !important`.

### Spacing Scale

```css
--sp-1: 4px;  --sp-2: 8px;  --sp-3: 12px; --sp-4: 16px;
--sp-5: 20px; --sp-6: 24px; --sp-8: 32px; --sp-9: 36px;
```

Standard panel padding: `--sp-9` (36px). Max content width: `--jimr-container-max` (1280px).

### Primary Button — `.fff-btn`

- Background: `--clr-accent` (#f4c542), Text: `--clr-primary` (#3d2f1f)
- Font: 14px (`--fs-sm`), weight 600
- **Button text must be sentence case in HTML** — never via `text-transform`
- No border — use `box-shadow` for depth only
- Border radius: 8px
- Padding: `--sp-2` `--sp-4` (8px 16px)
- Hover: background `--clr-btn-hover`, `transform: translate(-2px, -2px)`, shadow `--clr-shadow-lg`
- Dashicons: include with `margin-top: 3px`, 8px gap via flexbox `gap`
- **No icons in modal buttons** (Cancel, Save, OK) — keep modals clean

### Secondary / Cancel Button — `.fff-btn.fff-btn-secondary`

- Background: `--clr-txt-muted` (#64748b), Text: white (`!important`)
- Hover: background `#475569`

### Danger Button — `.fff-btn.fff-btn-danger`

- Background: `--clr-error` (#dc2626), Text: white
- Hover: background `#b91c1c`, `transform: translate(-2px, -2px)`

### Standard Dashicon Assignments

| Action | Icon class |
|--------|-----------|
| Reset / Undo | `dashicons-undo` |
| Save / Confirm | `dashicons-yes` |
| Add | `dashicons-plus-alt` |
| Delete / Clear | `dashicons-trash` |
| Copy | `dashicons-clipboard` |
| Export | `dashicons-download` |
| Import | `dashicons-upload` |

Tabs and inline text links do not use icons.

### Forge Header

Every plugin admin page includes the forge header:
- `assets/images/forge-banner.png`
- `assets/css/forge-header.css`

### Accessibility — Minimum WCAG 2.1 AA

- Focus style: `2px solid var(--clr-accent)`, `outline-offset: 2px`
- All icon-only buttons must have `aria-label`
- Keyboard navigation must work for all interactive elements
