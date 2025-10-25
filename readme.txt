=== Fluid Font Forge ===
Contributors: mijstrebor
Donate link: https://buymeacoffee.com/jimrweb
Tags: typography, fonts, responsive, clamp, fluid
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 5.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Advanced fluid typography calculator with CSS clamp() generation for responsive font scaling across all device sizes.

== Description ==

Fluid Font Forge is a comprehensive WordPress plugin that simplifies responsive typography management. Generate CSS clamp() functions with mathematical precision for perfectly scaled fonts across all devices.

**Key Features:**

* **Real-time CSS clamp() generation** - See your responsive font sizes calculated instantly
* **Multiple output formats** - CSS classes, custom properties, HTML tags, and Tailwind config
* **Interactive preview system** - Visual comparison of font sizes at different viewport widths
* **Mathematical scaling ratios** - Based on musical harmony principles (Major Second, Perfect Fourth, Golden Ratio, etc.)
* **Drag-and-drop size management** - Reorder your font scale with intuitive interface
* **Custom font preview** - Test with your actual web fonts using WOFF2 URLs
* **Copy-to-clipboard functionality** - One-click CSS copying for immediate use
* **Professional admin interface** - Clean, modern design that integrates seamlessly with WordPress

**Perfect for:**
* Web designers creating responsive typography systems
* Developers implementing fluid font scaling
* Theme creators needing consistent font hierarchies
* Anyone wanting professional typography without complex CSS calculations

**Technical Highlights:**
* Uses CSS clamp() for true fluid scaling between viewport breakpoints
* Supports both px and rem units with automatic conversion
* Mathematical progression ensures consistent visual hierarchy
* Accessibility-compliant with WCAG guidelines for readability
* Clean, semantic CSS output ready for production use

Transform your typography workflow with Fluid Font Forge - where responsive design meets mathematical precision.

**Accessibility Features:**

* WCAG 2.1 compliant interface
* Keyboard navigation support
* Screen reader friendly
* High contrast mode compatible
* Minimum font size recommendations for readability

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/fluid-font-forge` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Navigate to Tools > Fluid Font Forge in your WordPress admin menu to configure your typography settings and use the application.
4. Set your viewport range, root font sizes, and scaling ratios.
5. Add, edit, or reorder your font sizes using the interactive table.
6. Copy the generated CSS and paste it into your theme's stylesheet.

== Frequently Asked Questions ==

= What is CSS clamp() and why should I use it? =

CSS clamp() creates truly responsive typography that scales smoothly between minimum and maximum sizes based on viewport width. This eliminates the need for multiple media queries and creates more fluid, accessible designs.

= Can I use this with any WordPress theme? =

Yes! Fluid Font Forge generates standard CSS that works with any theme. You can use the generated classes, custom properties, or tag styles depending on your implementation preference.

= Does this work with custom fonts? =

Absolutely. The preview system supports WOFF2 font URLs so you can see exactly how your custom fonts will look at different sizes before implementing them.

= Is the generated CSS production-ready? =

Yes. The plugin generates clean, optimized CSS using best practices. The clamp() functions are supported in all modern browsers with automatic fallbacks.

= What browsers support CSS clamp()? =

All modern browsers support clamp() (Chrome 79+, Firefox 75+, Safari 13.1+, Edge 79+). 

= How do I choose the right scaling ratio? =

Start with Major Third (1.250) for body text or Perfect Fourth (1.333) for headlines. The Golden Ratio (1.618) creates dramatic size differences perfect for hero sections.

= Can I use different scales for mobile and desktop? =

The clamp() function automatically adjusts between your minimum and maximum viewport settings, creating a smooth scale across all devices.

= Can I export my settings? =

The plugin saves all your configurations in the WordPress database. For backup or migration, use WordPress's standard export/import tools or copy the generated CSS for manual implementation.

== Screenshots ==

1. **Complete Interface Overview** - Manage all your fluid typography from one intuitive dashboard
2. **Settings Panel** - Configure viewport ranges and scaling ratios with precision
3. **Control Center** - Switch between CSS Variables, Classes, HTML Tags, and Tailwind formats
4. **CSS Classes Output** - Generate production-ready CSS with semantic class names
5. **Live Preview** - Test your fonts at any viewport size with the interactive slider
6. **Scaling Visualization** - See exactly how your fonts scale across devices
7. **One-Click CSS Export** - Copy optimized code directly to your clipboard

== Other Notes ==

= Mathematical Ratios Explained =

* **Minor Second (1.067)**: Subtle progression, ideal for dense content
* **Major Second (1.125)**: Gentle scaling for readable body text
* **Minor Third (1.200)**: Balanced hierarchy for most designs
* **Major Third (1.250)**: Clear distinction between sizes
* **Perfect Fourth (1.333)**: Strong contrast for headlines
* **Golden Ratio (1.618)**: Dramatic scaling for hero sections
== Changelog ==

= 4.3.0 =
* Architecture: Implemented PHP namespaces (JimRWeb\FluidFontForge) for professional code organization
* Architecture: Renamed internal factory class for consistency with WordPress standards
* Enhanced: Improved code documentation and PHPDoc blocks throughout
* Technical: Modernized codebase to prevent naming conflicts with other plugins
* Technical: Prepared plugin structure for WordPress Plugin Directory submission
* Note: No functional changes - all features work identically to 4.2.0

= 4.2.0 =
* Added: Sample Text panel with interactive viewport slider for real-time font preview
* Added: Separate scaling controls for titles and body text
* Enhanced: Extracted modular controllers for better code organization
* Enhanced: Improved CSS documentation with KSS format
* Enhanced: Better error handling for custom font URLs
* Fixed: Preview Font feature now works with Google Fonts CSS API
* Fixed: Font name display shows actual family name instead of filename
* Fixed: Local file URLs properly blocked with helpful error messages

= 4.0.3 =
* Initial public release
* Complete responsive typography management system
* Interactive preview with real-time calculations
* Support for CSS classes, custom properties, HTML tags, and Tailwind config
* Drag-and-drop size management
* Mathematical scaling based on musical ratios
* Custom font preview support
* Professional admin interface with accessibility features
* Comprehensive error handling and input validation
* WordPress coding standards compliance

== Upgrade Notice ==

= 4.3.0 =
Architecture modernization with PHP namespaces. No functional changes - purely internal improvements for code quality and WordPress standards compliance.

= 4.2.0 =
Major feature update with Sample Text panel and improved preview system. Enhanced error handling and modular code structure.

= 4.0.3 =
Initial release of Fluid Font Forge. Install to start creating professional responsive typography systems with mathematical precision.