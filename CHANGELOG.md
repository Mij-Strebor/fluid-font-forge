# Changelog - Fluid Font Forge

All notable changes to Fluid Font Forge will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [5.3.3] — 2026-04-28

### Fixed
- **Forge header banner height** — Replaced viewport-relative `vh` units with fixed 420px height, preventing layout corruption on production sites with wider sidebars or shorter browser viewports.
- **About panel width overflow** — Fixed About panel stretching beyond the content area on sites with narrower content widths; now uses `width: 100%` with `max-width: 1280px`.
- **Modal max-height** — Replaced `90vh` with fixed `800px` on `.fff-modal-dialog` (admin-only tool targets desktop browsers).
- **Missing CSS variables** — Added `--clr-txt` and `--clr-shadow` custom properties that were referenced throughout templates but never defined, preventing color and shadow degradation.

### Changed
- **Quick Start** — Updated quick start documentation.
- **Version** — Bumped to 5.3.3.

---

## [5.3.2] — 2026-04-27

### Fixed
- **WordPress Plugin Check compliance** — Resolved all WP.org Plugin Check errors: added ABSPATH guard to plugin bootstrap, corrected I18n ordered placeholders (`%1$d/%2$d`, `%1$.1f/%2$.1f`) with `/* translators: */` comments in data validation, improved `$_FILES` sanitization with explicit `isset()` checks on individual array keys, and renamed global variable to properly prefixed `$fluid_font_forge_import_export`.

### Changed
- **Version** — Bumped to 5.3.2.

---

## [5.3.1] — 2026-04-27

### Changed
- **CSS variable consolidation** — Replaced hardcoded `rgba()`, hex colors, and pixel values in admin templates with CSS custom properties; added `--clr-info-box-bg`, `--clr-accent-box-bg`, `--clr-sample-box-bg`, `--clr-code-border`, and `--clr-code-text` to the design token system.
- **WordPress compatibility** — Tested up to WordPress 6.9.
- **PHP requirement** — Updated minimum PHP requirement to 8.2.
- **Version** — Bumped to 5.3.1.

### Fixed
- **Version string** — Corrected version string consistency.

---

## [5.3.0] - 2026-01-26

### New Features

#### Import/Export Settings
- **Complete settings backup and migration** - Export all Fluid Font Forge configurations as JSON files for backup, version control, or site migration
- **One-click export** - Download button with `dashicons-download` icon generates timestamped JSON file containing:
  - Global settings (viewport sizes, root font sizes, scaling ratios, unit type)
  - All font size definitions across CSS Classes, Variables, HTML Tags, and Tailwind tabs
  - Active tab selection and UI state
- **Safe import with validation** - Upload button with `dashicons-upload` icon accepts JSON files with comprehensive validation:
  - File type verification (JSON only)
  - Data structure validation
  - Range checking for all numeric values
  - Error handling with user-friendly messages
- **Migration and collaboration** - Transfer complete typography systems between WordPress installations or share configurations with team members
- **Version control integration** - JSON format works seamlessly with Git for tracking typography changes alongside codebase

**Use Cases:**
- Backup before making experimental changes to typography
- Migrate typography systems from staging to production
- Share standardized typography configurations across client sites
- Track typography evolution through version control
- Create library of reusable typography presets

**Technical Implementation:**
- New `class-fff-import-export.php` - Import/Export controller with file handling and validation
- New `fff-import-export-init.php` - WordPress action hooks and initialization
- New `class-fff-data-foundation.php` - Data validation foundation for safe imports
- JSON format with human-readable structure for manual editing if needed
- WordPress admin AJAX handlers for file upload/download
- Nonce verification and capability checks for security

#### Button Standardization with Dashicons
- **Consistent button styling** - All buttons now follow unified design with Dashicons integration
- **Icon implementation** - Added WordPress Dashicons to all action buttons with proper vertical alignment:
  - Reset buttons: `dashicons-undo` (↶ undo arrow)
  - Save button: `dashicons-yes` (✓ checkmark)
  - Add Size: `dashicons-plus-alt` (➕ plus sign)
  - Clear All: `dashicons-trash` (🗑 trash icon)
  - Copy/Copy All: `dashicons-clipboard` (📋 clipboard)
  - Export/Import: `dashicons-download`/`dashicons-upload`
- **Sentence case text** - Updated all button text from lowercase to sentence case for better accessibility and professionalism
  - "reset" → "Reset", "save" → "Save", "add size" → "Add Size", etc.
- **Standardized padding** - Removed inline style overrides, using consistent 8px 16px padding across all buttons
- **Improved UX** - 8px gap between icons and text via flexbox, margin-top: 3px for perfect icon alignment with Inter font

**Buttons Updated:**
- Header Controls: Reset Font, Save
- Settings Panel: Reset Settings
- Table Actions: Add Size, Reset, Clear All
- Modal Dialogs: Cancel, Save (no icons for clean modal design)
- CSS Output: Copy, Copy All

#### Skip Entries Feature
- **Skip toggle for font size entries** - New checkbox-style toggle (☑/☐) in the Actions column allows excluding font sizes from CSS output while maintaining their position in the typographic scale progression
- **Visual feedback** - Skipped entries show:
  - Strikethrough text with `[SKIP]` badge in data table
  - Diagonal stripe pattern background with reduced opacity
  - Grayed out appearance (50% opacity) in preview panels
- **Smart CSS generation** - Skipped entries are excluded from all CSS output formats (Classes, Variables, Tags, Tailwind) while maintaining mathematical progression for adjacent entries
- **Flexible spacing control** - Create larger gaps between font sizes without regenerating entire scales or losing mathematical relationships

**Use Cases:**
- Generate a complete scale and skip intermediate sizes for wider spacing
- Maintain scale integrity while reducing CSS output size
- Fine-tune typographic hierarchy without recalculating ratios
- Create custom spacing patterns (e.g., skip every other size for double gaps)

#### Enhanced Tooltips
- **Action button tooltips** - Added helpful tooltips to all table action buttons:
  - Edit: "Edit this font size"
  - Skip: "Click to skip in CSS output" / "Click to include in CSS output"
  - Delete: "Delete this font size"
  - Add Size: "Add a new font size to the current tab"
  - Reset: "Reset all font sizes to default values for this tab"
  - Clear All: "Remove all font sizes from the current tab"

#### Documentation Updates
- **New Feature callout** - Added "New Feature" section to How to Use panel explaining skip functionality
- **Updated all documentation** - README, Quick Start, and User Manual include skip feature examples and best practices

### Technical Details

**New Files:**
- `includes/class-fff-import-export.php` - Import/Export controller with file handling and validation logic
- `includes/fff-import-export-init.php` - WordPress action hooks and AJAX handler initialization
- `includes/class-fff-data-foundation.php` - Data validation foundation for safe imports

**Files Modified:**
- `templates/admin/header-controls.php` (lines 23-44) - Added Import/Export buttons with file handling, updated Reset and Save buttons with icons and sentence case text
- `templates/admin/settings-panel.php` (lines 21-24) - Added icon to Reset Settings button, sentence case text
- `assets/js/admin-script.js` (lines 1093-1107, 2240-2241) - Added icons to table action buttons, sentence case for modal buttons
- `assets/js/css-generator.js` (lines 420-441) - Changed Copy buttons from `.fff-copy-btn` to `.fff-btn`, added clipboard icons
- `assets/css/admin-styles.css` (lines 791-861) - Deprecated `.fff-copy-btn` class (backward compatible)
- `assets/css/admin-styles.css` (lines 1561-1590) - Added `.size-row.skipped` styling with stripe pattern
- `templates/admin/how-to-use-panel.php` (lines 52-55) - New Feature callout box
- Documentation files - Version updates and skip feature documentation

**Breaking Changes:** None - Fully backward compatible. Existing font size data loads correctly, and the `skipped` property defaults to `false` for all existing entries.

**Browser Compatibility:** Modern browsers (Chrome 79+, Firefox 75+, Safari 13.1+, Edge 79+)

---

## [5.1.3] - 2025-11-23

### Changed
- **Namespace update** - Changed PHP namespace from `JimRWeb\FluidFontForge` to `JimRForge\FluidFontForge`
- **CSS class prefix change** - All CSS classes changed from `fcc-` prefix to `fff-` prefix for better plugin identification
- **Branding update** - Updated all references from JimRWeb to JimRForge across documentation and source files

### Technical Notes
- Updated namespace in `class-fluid-font-forge.php` and `class-default-data-factory.php`
- Updated ~40 fully qualified namespace references throughout the codebase
- Changed CSS class prefixes in all CSS files, JavaScript files, and PHP templates
- No functional changes - all features work identically to 5.1.2

---

## [5.1.2] - 2025-11-12

### New Features

#### Settings Input Validation
- **Input validation with visual feedback** - Settings inputs now validate on blur with configurable limits:
  - Min Viewport Font Size: 8-32px
  - Max Viewport Font Size: 8-80px (must be ≥ Min Viewport Font Size)
  - Min Viewport Width: 200-992px
  - Max Viewport Width: 200-1920px (must be ≥ Min Viewport Width)
- **Red error modal** - Invalid values display red error message below input, matching input width
- **Auto-correction** - After 3 seconds, invalid values automatically correct to limit and update calculations
- **Dynamic validation** - Max values validate against current min values for consistency

#### Enhanced Input Interaction
- **Auto-select on focus** - Text automatically selected when clicking or tabbing into settings inputs
- **Enter key navigation** - Press Enter to move through inputs in visual tab order:
  - Min Root → Min Viewport → Max Root → Max Viewport → Min Scale dropdown
- **Keyboard-friendly workflow** - Streamlined data entry for power users

#### Improved Display Containers
- **Scrolling overflow** - Sample Text and Font Scale displays now scroll when content exceeds container:
  - Sample Text panel: max-height 400px with vertical scrollbar
  - Font Scale previews: max-height 500px with vertical scrollbar
- **Synchronized scrolling** - Min and Max Font Scale previews scroll together in sync
- **Smooth performance** - Uses requestAnimationFrame for optimal scroll synchronization

### Technical Details

**New Files:**
- `assets/js/settings-validation.js` - Validation logic and synchronized scrolling

**Files Modified:**
- `includes/class-fluid-font-forge.php` (lines 106-116, 259-266) - Added validation constants and enqueued validation script
- `templates/admin/sample-panel.php` (line 33) - Added overflow scrolling to Sample Text display
- `templates/admin/preview-section.php` (lines 38, 51) - Added overflow scrolling to Font Scale previews

**Breaking Changes:** None

**Browser Compatibility:** Modern browsers (Chrome 79+, Firefox 75+, Safari 13.1+, Edge 79+)

---

## [5.1.1] - 2025-11-03

### Bug Fixes

#### Critical Tab System Issues
- **Fixed Tailwind tab persistence** - Added 'tailwind' to VALID_TABS constant to prevent reset to 'class' on page refresh
- **Fixed tab switching crash** - Added missing ID attributes (`selected-code-title`, `generated-code-title`) to template elements preventing null reference errors
- **Fixed tab state persistence** - Added `savePanelStates()` call to `switchTab()` for immediate state persistence matching other control behavior

#### User Experience Improvements  
- **Fixed scroll jump behavior** - Removed `scrollIntoView()` call that caused unwanted page scrolling when selecting preview rows
- **Fixed REM unit display** - Implemented unit-aware display logic in Sample Text panel showing correct REM values with 3 decimal precision instead of hardcoded "px" suffix

### Content Updates

#### Community Panel Enhancements
- Updated section title: "Community & Support" → "Community & Tools"
- Updated FSF status: "Submitted to WordPress.org" → "In review at WordPress.org"  
- Added jimrforge.com project hub link with "Coming soon" messaging
- Shortened feedback button text: "Suggestions & Feedback" → "Feedback"
- Added new "⭐ Rate" button linking to WordPress.org reviews

### Technical Details

**Files Modified:**
- `includes/class-fluid-font-forge.php` (line 92) - Added 'tailwind' to VALID_TABS array
- `templates/admin/selected-css-panel.php` - Added `id="selected-code-title"` attribute
- `templates/admin/generated-css-panel.php` - Added `id="generated-code-title"` attribute
- `assets/js/admin-script.js` (lines 493-518, 1688-1699) - Tab persistence and scroll behavior fixes
- `assets/js/sample-panel.js` (lines 412-436) - Unit-aware size display implementation
- `templates/admin/community-panel.php` (lines 20, 30, 45, 60, 62-64) - Content updates

**Breaking Changes:** None - All changes are internal bug fixes and content updates

**Testing:** Comprehensive testing confirmed:
- All four tabs (Class, Variables, Tags, Tailwind) persist correctly across page refreshes
- Tab switching updates all UI elements without crashes
- Sample Text panel displays correct units (REM/PX) based on settings
- No unwanted scroll jumps when selecting preview items
- Community panel displays updated content with all links functional

---

## [5.1.0] - 2025-10-24

### Complete UI Modernization - MIF v3.0 Design System Alignment

Major visual overhaul bringing FFF into alignment with Media Inventory Forge's enhanced design language for consistent JimRForge brand recognition across all plugins.

### Added

#### Forge Header System
- **Dramatic 50vh integrated forge background** - Signature JimRForge visual identity
- Multi-layer fade system (noise texture, top fade, bottom fade, banner image)
- Seamless transition to page background with no hard edges
- `forge-header.css` (separate stylesheet for modularity)
- `forge-banner.png` (232KB Photoshop composite)
- Enqueued before main styles for proper CSS layering

#### Inter Font System
- **Self-hosted WOFF2 font files** - Modern sans-serif replacing Georgia serif
  - Inter-Regular.woff2 (22KB)
  - Inter-Medium.woff2 (23KB)
  - Inter-SemiBold.woff2 (23KB)
  - Inter-Bold.woff2 (23KB)
- @font-face declarations for all four weights
- font-display: swap for optimal performance
- Total font assets: 91KB (excellent compression)

### Changed

#### Color Palette Enhancement
All colors updated to match MIF v3.0 enhanced palette:
- **Primary**: #3C2017 → #3d2f1f (richer dark brown)
- **Secondary**: #5C3324 → #6d4c2f (warmer medium brown)
- **Accent**: #FFD700 → #f4c542 (refined muted gold)
- **Background**: #E8D8C3 → #faf6f0 (cleaner warm cream)
- **Card Background**: #F0E6DA → #ffffff (pure white for better contrast)
- **Text Primary**: #86400E → #6d4c2f (improved readability)
- **NEW**: --clr-accentHover: #e5b12d (button hover state)
- **NEW**: --clr-link: #c97b3c (burnt orange for WCAG AA link contrast)

#### Typography Scale Upgrade (16px Base)
Entire typography scale upgraded for better accessibility:
- Extra Small: 10px → 12px
- Small: 12px → 14px
- **Base: 14px → 16px** (WCAG 2.1 accessibility improvement)
- Large: 16px → 18px
- Extra Large: 18px → 20px
- 2xLarge: 20px → 24px
- **NEW**: 3xLarge: 32px (for prominent H1 titles)

#### Button System Standardization
Updated to match MIF's clean button design:
- **Text Color**: Red (#9C0202) → Dark Brown (--clr-primary)
- **Border**: Removed 2px red border (cleaner appearance)
- **Font Style**: Italic → Normal (professional)
- **Text Transform**: Lowercase → None (standard capitalization)
- **Border Radius**: 3px → 6px (softer, modern corners)
- **Maintained**: Gold background, 600 weight, hover effects

#### Typography System
- **All Georgia serif → Inter sans-serif** throughout interface
- Added font family variables (--fff-font-body, --fff-font-heading, --fff-font-mono)
- Updated all h1, h2, and title elements to use Inter
- Consistent with MIF's modern typography approach

#### Layout Refinements
- Border radius standard: 3px → 6px (softer edges)
- Border radius large: 5px → 8px (consistent with MIF)
- Maintained 1280px max-width strategy
- Updated table of contents with new section 0 (Inter Font Loading)

### Improved

#### Visual Consistency
- FFF now matches MIF's visual language exactly
- Consistent JimRForge brand identity across plugin ecosystem
- Professional, modern appearance
- Enhanced visual hierarchy with better typography scale

#### Accessibility
- 16px base font size meets WCAG 2.1 Level AA
- Improved color contrast with new palette
- Link color (--clr-link) optimized for accessibility
- Better readability with Inter font

#### Performance
- Self-hosted fonts (no external requests)
- WOFF2 format (best compression)
- font-display: swap prevents FOIT
- Total added assets: ~323KB (fonts + banner)

### Documentation

- Added `docs/FFF-VS-MIF-DESIGN-COMPARISON.md` - Comprehensive design system comparison
- Updated table of contents with new line numbers
- Added section 0 for Inter font loading
- Updated all font-family references in documentation
- Enhanced CSS comments for new design tokens

### Technical Details

**Files Modified:**
- `assets/css/admin-styles.css` - Added fonts, updated all design tokens
- `includes/class-fluid-font-forge.php` - Enqueue forge-header.css

**Files Added:**
- `assets/css/forge-header.css` - Forge header system
- `assets/fonts/Inter-*.woff2` (4 files) - Inter font family
- `assets/images/forge-banner.png` - Dramatic forge background
- `docs/FFF-VS-MIF-DESIGN-COMPARISON.md` - Design analysis

**Breaking Changes:** None - All changes are visual only

**Testing:** Load FFF admin page in WordPress to verify:
- Forge header displays with dramatic background
- Inter font loaded correctly
- Colors match MIF palette
- Buttons have clean, modern appearance
- No console errors

---

## [5.0.0] - 2025-10-24

### Major CSS Refactoring - Forge Design System Compliance

Complete modernization of CSS architecture implementing official Forge Design System naming conventions.

### Changed

#### CSS Variable System (66 variables migrated)
- **Spacing Variables**: Migrated from `--jimr-space-*` to `--spc-*` with t-shirt sizing
  - `--jimr-space-1` → `--spc-xs` (4px)
  - `--jimr-space-2` → `--spc-sm` (8px)
  - `--jimr-space-3` → `--spc-md` (12px)
  - `--jimr-space-4` → `--spc-base` (16px)
  - `--jimr-space-5` → `--spc-lg` (20px)
  - `--jimr-space-6` → `--spc-xl` (24px)

- **Typography Scale**: Migrated from `--jimr-font-*` to `--fnt-*`
  - `--jimr-font-xs` → `--fnt-extraSmall` (10px)
  - `--jimr-font-sm` → `--fnt-small` (12px)
  - `--jimr-font-base` → `--fnt-base` (14px)
  - `--jimr-font-lg` → `--fnt-large` (16px)
  - `--jimr-font-xl` → `--fnt-extraLarge` (18px)
  - `--jimr-font-2xl` → `--fnt-2xLarge` (20px)

- **Shadow System**: Moved from color category to proper shadow prefix
  - `--clr-shadow-sm` → `--shd-small`
  - `--clr-shadow` → `--shd-standard`
  - `--clr-shadow-md` → `--shd-medium`
  - `--clr-shadow-lg` → `--shd-large`
  - `--clr-shadow-xl` → `--shd-extraLarge`
  - `--clr-shadow-color` → `--shd-baseColor`

- **Border System**: Separated from colors to proper border prefix
  - `--clr-bdr` → `--brd-standard`
  - `--clr-bdr-dark` → `--brd-dark`
  - `--clr-btn-bdr` → `--brd-button`

- **Border Radius**: Migrated from jimr prefix
  - `--jimr-border-radius` → `--rad-standard` (3px)
  - `--jimr-border-radius-lg` → `--rad-large` (5px)

- **Transitions**: Migrated from jimr prefix
  - `--jimr-transition` → `--trn-standard` (0.2s)
  - `--jimr-transition-slow` → `--trn-slow` (0.3s)

- **Grayscale Palette**: Removed jimr prefix, added clr prefix
  - `--jimr-gray-50` through `--jimr-gray-900` → `--clr-gray50` through `--clr-gray900`

- **Color Naming**: Improved consistency with camelCase
  - `--clr-page-bg` → `--clr-pageBackground`
  - `--clr-card-bg` → `--clr-cardBackground`
  - `--clr-txt` → `--clr-textPrimary`
  - `--clr-txt-light` → `--clr-textLight`
  - `--clr-txt-muted` → `--clr-textMuted`
  - `--clr-btn-hover` → `--clr-buttonHover`
  - `--clr-btn-txt` → `--clr-buttonText`
  - `--clr-success-dark` → `--clr-successDark`
  - `--clr-danger-dark` → `--clr-dangerDark`
  - `--clr-info-dark` → `--clr-infoDark`
  - `--clr-warning-dark` → `--clr-warningDark`

- **Drag & Drop**: Fixed naming and proper category separation
  - `--clr-drag-hover-bkg` → `--clr-dragHoverBackground`
  - `--clr-drag-hover-bdr` → `--brd-dragHover`
  - `--clr-drag-active-bkg` → `--clr-dragActiveBackground`
  - `--clr-drag-active-bdr` → `--brd-dragActive`
  - `--clr-drag-active-sdw` → `--shd-dragActive`
  - `--clr-drag-drop-point` → `--clr-dragDropPoint`

### Improved

#### CSS Organization
- Added comprehensive table of contents at file header with line numbers
- Improved section documentation with clearer descriptions
- Added usage examples to complex variable groups
- Updated all documentation blocks to reference v5.0.0

#### Code Quality
- Removed duplicate utility class section (Tailwind replacement utilities)
- Consolidated utility classes under single section with fff- prefix
- Reduced file size from 2,883 to 2,826 lines (57 lines removed)
- Zero legacy `--jimr-` references remaining
- 330+ variable usages updated throughout file

### Technical Details

#### Forge Design System Compliance
All CSS variables now follow official 3-character prefix convention:
- `--clr-*` (Colors)
- `--shd-*` (Shadows)
- `--brd-*` (Borders)
- `--rad-*` (Border radius)
- `--fnt-*` (Typography)
- `--spc-*` (Spacing - padding, margin, gap)
- `--trn-*` (Transitions)

#### Naming Patterns
- T-shirt sizing for scalable values: xs, sm, md, base, lg, xl
- camelCase for multi-word descriptive names
- Consistent pattern: `--prefix-descriptiveName`

### Migration Notes

**Breaking Changes**: None - All changes are internal to CSS file only.

**Testing**: Visual regression testing confirms zero visual changes to interface.

**Performance**: File size unchanged (~60KB), gzip compression handles variable name changes efficiently.

### Documentation Added
- `docs/CSS-VARIABLE-AUDIT.md` - Complete inventory of all 66 CSS variables with migration map
- `docs/CSS-REFACTORING-COMPLETED.md` - Detailed completion report with statistics
- `docs/CSS-OPTIMIZATION-PLAN.md` - Conservative optimization strategy and results
- `docs/FFF-MODERNIZATION-PLAN.md` - Overall modernization roadmap

---

## [4.3.4] - 2024

### Fixed
- Version synchronization across plugin files
- Minor bug fixes and stability improvements

---

## [4.3.1] - 2024

### Added
- Drag and drop reordering for size entries
- Autosave functionality with status indicators
- Enhanced preview panels with viewport slider
- Sample space display for margin/padding/gap visualization

### Improved
- UI polish with professional button styling
- Enhanced color scheme with JimRForge brand colors
- Better mobile responsiveness
- Improved table layouts and data display

---

## [4.0.0] - 2024

### Added
- Complete UI redesign with card-based layout
- Interactive sample text preview with viewport slider
- Tabs for different output formats (Classes, Variables, Tags, Tailwind)
- Loading states and smooth animations
- WordPress admin integration improvements

### Changed
- Migrated to modern CSS custom properties
- Improved typography hierarchy
- Enhanced form controls and inputs
- Better accessibility features

---

## [3.x] - 2023

### Added
- Initial release of Fluid Font Forge
- Core clamp() calculation engine
- Basic admin interface
- Settings persistence via WordPress Options API
- Multiple output format support

---

**Note**: Versions prior to 4.0.0 had limited changelog documentation. Version 5.0.0 marks the beginning of comprehensive changelog maintenance following Keep a Changelog format.
