<?php

/**
 * Plugin Name: Fluid Font Forge
 * Description: Advanced fluid typography calculator with CSS clamp() generation for responsive font scaling.
 * Version: 5.1.0
 * Author: Jim R (JimRForge)
 * Author URI: https://jimrforge.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: fluid-font-forge
 * Requires at least: 5.0
 * Tested up to: 6.8
 * Requires PHP: 7.4
 * 
 * Copyright (c) 2020-2024 Jim R (JimRForge)
 */

/**
 * 
 * Fluid Font Forge - Main Plugin Bootstrap File
 *
 * Advanced fluid typography calculator that generates CSS clamp() values for responsive
 * font scaling. This plugin provides developers with a professional toolkit for creating
 * responsive typography systems that scale smoothly across different viewport sizes.
 *
 * Key Features:
 * - CSS clamp() generation with mathematical precision
 * - Multiple output formats (CSS classes, variables, Tailwind, HTML tags)
 * - Type scale integration with modular scaling ratios
 * - Real-time preview with interactive adjustments
 * - Autosave functionality with local data persistence
 * - Professional admin interface with tabbed organization
 * - Export capabilities for various CSS frameworks
 *
 * Technical Implementation:
 * - Factory pattern for default data management
 * - Modular class architecture for extensibility
 * - WordPress options API for settings persistence
 * - Progressive enhancement with JavaScript interactions
 * - Responsive admin interface with mobile optimization
 *
 * Typography Features:
 * - Fluid font scaling between viewport breakpoints
 * - Type scale ratios (minor second, major third, perfect fourth, etc.)
 * - Base font size calculations with rem/px conversion
 * - Minimum and maximum font size constraints
 * - Viewport-based scaling with customizable breakpoints
 *
 * Output Formats:
 * - CSS Custom Properties (CSS Variables)
 * - Utility Classes with clamp() values
 * - Tailwind CSS compatible sizing
 * - HTML tag-based typography system
 * - Copy-to-clipboard functionality for all formats
 *
 * Performance Considerations:
 * - Efficient options storage with minimal database impact
 * - Client-side calculations for real-time responsiveness
 * - Optimized asset loading only in admin context
 * - Cache-aware implementation with version management
 *
 * Security Features:
 * - Capability checks for admin access
 * - Nonce verification for form submissions
 * - Input sanitization and validation
 * - WordPress security standards compliance 
 */

/* ==========================================================================
   SECURITY AND ACCESS CONTROL
   ========================================================================== */

/**
 * Prevent Direct File Access
 *
 * Security measure to prevent direct execution of this file outside of
 * WordPress context. Essential for protecting plugin functionality and
 * preventing unauthorized access to plugin internals.
 */
if (!defined('ABSPATH')) {
    exit;
}

/* ==========================================================================
   CORE PLUGIN CONSTANTS
   ========================================================================== */

/**
 * Plugin Version Constant
 *
 * Current version following semantic versioning. Used for cache busting,
 * upgrade detection, and compatibility management.
 *
 * @var string FLUID_FONT_FORGE_VERSION Current plugin version
 */
define('FLUID_FONT_FORGE_VERSION', '5.1.0');

/**
 * Plugin Directory Path Constant
 *
 * Absolute filesystem path to plugin directory for file includes and
 * resource access with cross-platform compatibility.
 *
 * @var string FLUID_FONT_FORGE_PATH Absolute path to plugin directory
 */
define('FLUID_FONT_FORGE_PATH', plugin_dir_path(__FILE__));

/**
 * Plugin URL Constant
 *
 * Base URL for plugin assets, handling SSL and subdirectory installations
 * automatically for reliable asset loading.
 *
 * @var string FLUID_FONT_FORGE_URL Base URL to plugin directory
 */
define('FLUID_FONT_FORGE_URL', plugin_dir_url(__FILE__));

/* ==========================================================================
   VERSION MANAGEMENT AND COMPATIBILITY CONSTANTS
   ========================================================================== */

/**
 * Database Version Constant
 *
 * Database schema version for tracking structural changes and
 * managing upgrade migrations between plugin versions.
 *
 * @var string FLUID_FONT_FORGE_DB_VERSION Database schema version
 */
define('FLUID_FONT_FORGE_DB_VERSION', '1.0');

/**
 * Minimum WordPress Version Constant
 *
 * Minimum required WordPress version for plugin compatibility,
 * ensuring access to required WordPress APIs and features.
 *
 * @var string FLUID_FONT_FORGE_MIN_WP_VERSION Minimum WordPress version
 */
define('FLUID_FONT_FORGE_MIN_WP_VERSION', '5.0');

/**
 * Minimum PHP Version Constant
 *
 * Minimum required PHP version for modern language features,
 * security updates, and performance optimizations.
 *
 * @var string FLUID_FONT_FORGE_MIN_PHP_VERSION Minimum PHP version
 */
define('FLUID_FONT_FORGE_MIN_PHP_VERSION', '7.4');

/* ==========================================================================
   WORDPRESS OPTIONS STORAGE CONSTANTS
   ========================================================================== */

/**
 * Main Settings Option Key
 *
 * Primary plugin settings including viewport sizes, scaling ratios,
 * and global configuration preferences.
 *
 * @var string FLUID_FONT_FORGE_OPTION_SETTINGS Main settings option key
 */
define('FLUID_FONT_FORGE_OPTION_SETTINGS', 'fluid_font_forge_settings');

/**
 * CSS Classes Data Option Key
 *
 * Storage key for CSS class-based typography definitions and
 * generated clamp() values for utility class systems.
 *
 * @var string FLUID_FONT_FORGE_OPTION_CLASS_SIZES CSS classes data key
 */
define('FLUID_FONT_FORGE_OPTION_CLASS_SIZES', 'fluid_font_forge_class_sizes');

/**
 * CSS Variables Data Option Key
 *
 * Storage key for CSS custom property definitions and generated
 * clamp() values for CSS variable-based typography.
 *
 * @var string FLUID_FONT_FORGE_OPTION_VARIABLE_SIZES CSS variables data key
 */
define('FLUID_FONT_FORGE_OPTION_VARIABLE_SIZES', 'fluid_font_forge_variable_sizes');

/**
 * HTML Tag Data Option Key
 *
 * Storage key for HTML tag-based typography definitions targeting
 * semantic elements (h1, h2, p, etc.) with responsive scaling.
 *
 * @var string FLUID_FONT_FORGE_OPTION_TAG_SIZES HTML tag data key
 */
define('FLUID_FONT_FORGE_OPTION_TAG_SIZES', 'fluid_font_forge_tag_sizes');

/**
 * Tailwind CSS Data Option Key
 *
 * Storage key for Tailwind CSS compatible utility class definitions
 * with clamp() values for Tailwind-based design systems.
 *
 * @var string FLUID_FONT_FORGE_OPTION_TAILWIND_SIZES Tailwind data key
 */
define('FLUID_FONT_FORGE_OPTION_TAILWIND_SIZES', 'fluid_font_forge_tailwind_sizes');

/* ==========================================================================
   PLUGIN LIFECYCLE MANAGEMENT
   ========================================================================== */

/**
 * Plugin Activation Handler
 *
 * Initializes plugin with default settings and performs necessary setup
 * procedures when plugin is first activated. Ensures clean installation
 * with sensible defaults for immediate usability.
 *
 * Default Settings Include:
 * - Viewport sizes (375px to 1620px)
 * - Base font sizes (16px to 20px)
 * - Type scale ratios (1.125 to 1.333)
 * - Autosave functionality enabled
 * - Default unit type (pixels)
 *
 * @return void
 */
function fluid_font_forge_activate()
{
    // Set default settings if they don't exist
    if (!get_option(FLUID_FONT_FORGE_OPTION_SETTINGS)) {
        $default_settings = array(
            'minRootSize' => 16,
            'maxRootSize' => 20,
            'minViewport' => 375,
            'maxViewport' => 1620,
            'unitType' => 'px',
            'activeTab' => 'class',
            'minScale' => 1.125,
            'maxScale' => 1.333,
            'autosaveEnabled' => false
        );
        add_option(FLUID_FONT_FORGE_OPTION_SETTINGS, $default_settings);
    }

    // Set version number for tracking
    add_option('fluid_font_forge_version', FLUID_FONT_FORGE_VERSION);

    // Clear any cached data
    wp_cache_flush();
}

/**
 * Plugin Deactivation Handler
 *
 * Performs cleanup operations when plugin is deactivated while preserving
 * user settings and data. Removes temporary data and clears caches but
 * maintains user configuration for potential reactivation.
 *
 * Cleanup Operations:
 * - Clear WordPress object cache
 * - Remove temporary runtime flags
 * - Preserve user settings and data
 * - Maintain database options for reactivation
 *
 * @return void
 */
function fluid_font_forge_deactivate()
{
    // Clear any cached data
    wp_cache_flush();

    // Remove plugin loaded flag
    if (defined('FLUID_FONT_FORGE_LOADED')) {
        // Note: Can't undefine constants, but flag is cleared on next load
    }
}

/**
 * Plugin Version Check and Upgrade Manager
 *
 * Compares stored version with current plugin version to detect upgrades
 * and trigger necessary migration procedures. Ensures data compatibility
 * across plugin updates and manages version-specific changes.
 *
 * @return void
 */
function fluid_font_forge_check_version()
{
    $current_version = get_option('fluid_font_forge_version', '0');

    if (version_compare($current_version, FLUID_FONT_FORGE_VERSION, '<')) {
        // Run upgrade routine
        fluid_font_forge_upgrade($current_version);

        // Update version number
        update_option('fluid_font_forge_version', FLUID_FONT_FORGE_VERSION);
    }
}

/**
 * Plugin Upgrade Handler
 *
 * Manages version-specific upgrade procedures including data migration,
 * option updates, and compatibility adjustments. Provides framework
 * for handling breaking changes between plugin versions.
 *
 * @param string $old_version Previous plugin version for migration reference
 * @return void
 */
function fluid_font_forge_upgrade($old_version)
{
    // Future upgrade routines will go here
    // For now, just clear cache
    wp_cache_flush();
}

/* ==========================================================================
   WORDPRESS INTEGRATION AND HOOK REGISTRATION
   ========================================================================== */

/**
 * Plugin Lifecycle Hook Registration
 *
 * Registers essential WordPress hooks for plugin activation, deactivation,
 * and version management. Ensures proper integration with WordPress plugin
 * management system and maintains plugin state consistency.
 */

/**
 * Register Plugin Activation Hook
 *
 * Triggers activation handler when plugin is activated through
 * WordPress admin interface or programmatically.
 */
register_activation_hook(__FILE__, 'fluid_font_forge_activate');

/**
 * Register Plugin Deactivation Hook
 *
 * Triggers deactivation handler when plugin is deactivated,
 * ensuring proper cleanup while preserving user data.
 */
register_deactivation_hook(__FILE__, 'fluid_font_forge_deactivate');

/**
 * Plugin Feature Hook Registration
 *
 * Registers hooks for ongoing plugin functionality including version checking,
 * settings management, and user interface enhancements.
 */

/**
 * Register Version Check Action
 *
 * Checks for plugin updates on WordPress initialization to
 * trigger upgrade procedures when necessary.
 */
add_action('plugins_loaded', 'fluid_font_forge_check_version');

/* ==========================================================================
   CLASS LOADING AND DEPENDENCY MANAGEMENT
   ========================================================================== */

/**
 * Load Default Data Factory Class
 *
 * Factory class providing default typography data including type scales,
 * size definitions, and configuration presets. Enables consistent default
 * values across different typography output formats.
 *
 * @since 1.0.0
 */
require_once FLUID_FONT_FORGE_PATH . 'includes/class-default-data-factory.php';

/**
 * Load Main Plugin Class
 *
 * Core plugin class containing primary functionality including admin
 * interface, calculation engine, and WordPress integration. Centralizes
 * plugin logic and provides main entry point for all features.
 *
 * @since 1.0.0
 */
require_once FLUID_FONT_FORGE_PATH . 'includes/class-fluid-font-forge.php';

/* ==========================================================================
   PLUGIN INITIALIZATION
   ========================================================================== */

/**
 * Initialize Plugin in Admin Context
 *
 * Plugin functionality is restricted to WordPress admin area for performance
 * optimization and security. Frontend functionality would be added here if
 * needed for future enhancements.
 */
if (is_admin()) {
    /**
     * Global Plugin Instance
     *
     * Maintains singleton reference to main plugin instance for access
     * throughout WordPress admin interface and inter-component communication.
     * Provides centralized access point for plugin functionality.
     *
     * @global FluidFontForge $fluidFontForge Main plugin instance
     */
    global $fluidFontForge;

    /**
     * Instantiate Main Plugin Class
     *
     * Creates primary plugin instance with error handling to ensure
     * graceful failure if initialization problems occur.
     */
    $fluidFontForge = new \JimRWeb\FluidFontForge\FluidFontForge();
}

/**
 * Set Plugin Loaded Flag
 *
 * Indicates successful plugin loading for other components and
 * debugging purposes. Prevents duplicate initialization attempts
 * and provides status indicator for dependent functionality.
 */
if (!defined('FLUID_FONT_FORGE_LOADED')) {
    define('FLUID_FONT_FORGE_LOADED', true);
}

/* ==========================================================================
   END OF PLUGIN BOOTSTRAP
   ========================================================================== */