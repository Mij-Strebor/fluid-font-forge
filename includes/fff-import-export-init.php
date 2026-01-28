<?php
/**
 * Import/Export Integration - Bootstrap
 *
 * Loads and initializes the import/export functionality for Fluid Font Forge.
 * This file handles all setup required for the Data Foundation features.
 *
 * @package    FluidFontForge
 * @subpackage Integration
 * @since      5.3.0
 * @version    5.3.0
 * @author     Jim R Forge
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load Data Foundation Trait
require_once FLUID_FONT_FORGE_PATH . 'includes/class-fff-data-foundation.php';

// Load Import/Export Handler
require_once FLUID_FONT_FORGE_PATH . 'includes/class-fff-import-export.php';

// Initialize Import/Export Handler (only in admin)
if (is_admin()) {
    add_action('plugins_loaded', function() {
        global $fluid_font_forge, $fff_import_export;

        // Wait for main plugin to initialize
        if (isset($fluid_font_forge) && $fluid_font_forge instanceof \JimRForge\FluidFontForge\FluidFontForge) {
            $fff_import_export = new \JimRForge\FluidFontForge\FFF_ImportExport($fluid_font_forge);
        }
    }, 20); // Priority 20 to ensure main plugin loads first
}
