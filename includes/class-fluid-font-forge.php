<?php

/**
 * Fluid Font Forge Main Class
 * 
 * @package FluidFontForge
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Fluid Font Forge - Complete Unified Class
 */
class FluidFontForge
{
    // ========================================================================
    // CORE CONSTANTS SYSTEM
    // ========================================================================

    // Configuration Constants
    const PLUGIN_SLUG = 'fluid-font-forge';
    const NONCE_ACTION = 'fluid_font_nonce';

    // Default Values - PRIMARY CONSTANTS 
    // Why 16px: Browser default font size - ensures accessibility baseline
    const DEFAULT_MIN_ROOT_SIZE = 16;
    // Why 20px: 25% larger than default - provides good contrast without being jarring
    const DEFAULT_MAX_ROOT_SIZE = 20;
    // Why 375px: iPhone SE width - covers smallest modern mobile devices
    const DEFAULT_MIN_VIEWPORT = 375;
    // Why 1620px: Laptop/desktop sweet spot - before ultra-wide displays
    const DEFAULT_MAX_VIEWPORT = 1620;
    // Why 1.125: Major Second ratio - subtle but noticeable size differences on mobile
    const DEFAULT_MIN_SCALE = 1.125;
    // Why 1.333: Perfect Fourth ratio - creates strong hierarchy on larger screens
    const DEFAULT_MAX_SCALE = 1.333;
    // Why 1.2: Headings need tighter spacing for visual impact and hierarchy
    const DEFAULT_HEADING_LINE_HEIGHT = 1.2;
    // Why 1.4: Body text needs comfortable reading spacing (WCAG accessibility)
    const DEFAULT_BODY_LINE_HEIGHT = 1.4;

    // Browser and system constants
    // Why 16px: Universal browser default - foundation for rem calculations and accessibility
    const BROWSER_DEFAULT_FONT_SIZE = 16;
    // Why 16px base: 1rem = 16px by default - critical for rem/px conversions
    const CSS_UNIT_CONVERSION_BASE = 16;

    // WordPress Options Keys - use global constants
    // Why constants: Prevents typos, ensures consistency across the codebase
    const OPTION_SETTINGS = FLUID_FONT_FORGE_OPTION_SETTINGS;
    // Why separate keys: Allows independent management of settings and size arrays
    const OPTION_CLASS_SIZES = FLUID_FONT_FORGE_OPTION_CLASS_SIZES;
    const OPTION_VARIABLE_SIZES = FLUID_FONT_FORGE_OPTION_VARIABLE_SIZES;
    const OPTION_TAG_SIZES = FLUID_FONT_FORGE_OPTION_TAG_SIZES;
    const OPTION_TAILWIND_SIZES = FLUID_FONT_FORGE_OPTION_TAILWIND_SIZES;

    // Valid Options
    // Why these units: px and rem are most common for font sizing, ensuring compatibility
    const VALID_UNITS = ['px', 'rem'];
    // Why these tabs: Covers all three size management methods provided by the plugin
    const VALID_TABS = ['class', 'vars', 'tag'];

    // Validation Ranges
    // Why 1-100px: Prevents unusably small (<1px) or absurdly large (>100px) root sizes
    const MIN_ROOT_SIZE_RANGE = [1, 100];
    // Why 200-5000px: Covers feature phones to ultra-wide displays safely
    const VIEWPORT_RANGE = [200, 5000];
    // Why 0.8-3.0: Below 0.8 is unreadable, above 3.0 creates excessive spacing
    const LINE_HEIGHT_RANGE = [0.8, 3.0];
    // Why 1.0-3.0: Below 1.0 shrinks text, above 3.0 creates extreme size jumps
    const SCALE_RANGE = [1.0, 3.0];

    // ========================================================================
    // CLASS PROPERTIES
    // ========================================================================

    //
    private $default_settings;
    private $default_class_sizes;
    private $default_variable_sizes;
    private $default_tag_sizes;
    private $default_tailwind_sizes;
    private $assets_loaded = false;

    // ========================================================================
    // CORE INITIALIZATION
    // ========================================================================

    /**
     * Initialize the plugin
     *
     * Sets up default data, WordPress hooks, and admin interface components.
     * Only initializes in admin context for performance optimization.
     *
     * @since 4.1.0
     */
    public function __construct()
    {
        $this->init_defaults();
        $this->init_hooks();
    }

    /**
     * Initialize default values using data factory
     */
    private function init_defaults()
    {
        $defaults = FluidFontForgeDefaultData::getAllDefaults();
        $this->default_settings = $defaults['settings'];
        $this->default_class_sizes = $defaults['classSizes'];
        $this->default_variable_sizes = $defaults['variableSizes'];
        $this->default_tag_sizes = $defaults['tagSizes'];
        $this->default_tailwind_sizes = $defaults['tailwindSizes'];
    }

    /**
     * Hook into WordPress admin_menu with high priority to prevent flattening
     */
    private function init_hooks()
    {
        // Use proper WordPress hook for menu registration
        add_action('admin_menu', [$this, 'add_admin_menu']);

        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets'], 10, 1);
        add_action('wp_ajax_fluifofo_save_font_clamp_sizes', [$this, 'save_sizes']);
        add_action('wp_ajax_fluifofo_save_font_clamp_settings', [$this, 'save_settings']);
    }

    // ========================================================================
    // ADMIN INTERFACE
    // ========================================================================

    /**
     * Add admin menu to WordPress Tools section
     * 
     * Creates a standalone menu item under Tools for Fluid Font Forge,
     * providing independent access without requiring external menu systems.
     * @since 4.1.0 
     * @return void
     */
    public function add_admin_menu()
    {
        // Add to WordPress Tools menu
        add_management_page(
            'Fluid Font Forge',                // Page title
            'Fluid Font Forge',                // Menu title
            'manage_options',                  // Capability
            'fluid-font-forge',                // Menu slug
            [$this, 'render_admin_page']       // Callback function
        );
    }

    /**
     * Enqueue admin scripts and styles
     *
     * Loads CSS and JavaScript assets only on the plugin's admin page.
     * Includes localized data for AJAX operations and plugin configuration.
     *
     * @since 4.1.0
     * @param string $hook Current admin page hook
     * @return void
     */
    public function enqueue_assets($hook)
    {
        if ($hook !== 'tools_page_fluid-font-forge') {
            return;
        }

        wp_enqueue_style(
            'fluid-font-forge-admin',
            FLUID_FONT_FORGE_URL . 'assets/css/admin-styles.css',
            [],
            FLUID_FONT_FORGE_VERSION
        );

        wp_enqueue_script(
            'fluifofo-utilities',
            FLUID_FONT_FORGE_URL . 'assets/js/utilities.js',
            [], // NO dependencies
            FLUID_FONT_FORGE_VERSION,
            true
        );

        wp_enqueue_script(
            'fluid-font-forge-unified-access',
            FLUID_FONT_FORGE_URL . 'assets/js/unified-size-access.js',
            ['fluifofo-utilities'], // Depends on utilities
            FLUID_FONT_FORGE_VERSION,
            true
        );

        // Enqueue sample panel controller
        wp_enqueue_script(
            'fluifofo-sample-panel',
            FLUID_FONT_FORGE_URL . 'assets/js/sample-panel.js',
            ['fluifofo-utilities', 'fluid-font-forge-unified-access'],
            FLUID_FONT_FORGE_VERSION,
            true
        );

        // Enqueue CSS generator controller
        wp_enqueue_script(
            'fluifofo-css-generator',
            FLUID_FONT_FORGE_URL . 'assets/js/css-generator.js',
            ['fluifofo-utilities', 'fluid-font-forge-unified-access'],
            FLUID_FONT_FORGE_VERSION,
            true
        );

        // Enqueue drag-drop controller
        wp_enqueue_script(
            'fluifofo-drag-drop',
            FLUID_FONT_FORGE_URL . 'assets/js/drag-drop-controller.js',
            ['fluifofo-utilities', 'fluid-font-forge-unified-access'],
            FLUID_FONT_FORGE_VERSION,
            true
        );

        // Enqueue main admin script (depends on all controllers)
        wp_enqueue_script(
            'fluifofo-admin',
            FLUID_FONT_FORGE_URL . 'assets/js/admin-script.js',
            ['fluifofo-utilities', 'fluid-font-forge-unified-access', 'fluifofo-sample-panel', 'fluifofo-css-generator', 'fluifofo-drag-drop'],
            FLUID_FONT_FORGE_VERSION,
            true
        );

        // Localize data for the UTILITIES script (loaded first)
        wp_localize_script('fluifofo-utilities', 'fluifofoAjax', [
            'nonce' => wp_create_nonce(self::NONCE_ACTION),
            'ajaxurl' => admin_url('admin-ajax.php'),
            'data' => [
                'settings' => $this->get_font_clamp_settings(),
                'classSizes' => $this->get_font_clamp_class_sizes(),
                'variableSizes' => $this->get_font_clamp_variable_sizes(),
                'tagSizes' => $this->get_font_clamp_tag_sizes(),
                'tailwindSizes' => $this->get_font_clamp_tailwind_sizes()
            ],
            'constants' => $this->get_all_constants(),
            'version' => FLUID_FONT_FORGE_VERSION
        ]);
    }

    /**
     * Get all plugin constants for JavaScript access
     *
     * Returns array of plugin constants for use in JavaScript via wp_localize_script.
     * Provides frontend access to PHP-defined configuration values.
     *
     * @since 4.1.0
     * @return array Associative array of all defined constants
     */
    public function get_all_constants()
    {
        return FluidFontForgeDefaultData::getConstants();
    }

    // ========================================================================
    // DATA GETTERS
    // ========================================================================

    /**
     * Get font clamp settings with caching
     * // Caches settings after first retrieval to optimize performance
     */
    public function get_font_clamp_settings()
    {
        static $cached_settings = null;

        if ($cached_settings === null) {
            $settings = wp_parse_args(
                get_option(self::OPTION_SETTINGS, []),
                $this->default_settings
            );
            if (!in_array($settings['activeTab'], self::VALID_TABS)) {
                $settings['activeTab'] = 'class';
                update_option(self::OPTION_SETTINGS, $settings);
            }

            $cached_settings = $settings;
        }

        return $cached_settings;
    }

    /**
     * Get font clamp class sizes with caching
     * // Caches sizes after first retrieval to optimize performance
     * // Uses default sizes if none are saved in options
     */
    public function get_font_clamp_class_sizes()
    {
        static $cached_sizes = null;
        if ($cached_sizes === null) {
            $cached_sizes = get_option(self::OPTION_CLASS_SIZES, $this->default_class_sizes);
        }
        return $cached_sizes;
    }

    /**
     * Get font clamp variable sizes with caching
     * // Caches sizes after first retrieval to optimize performance
     * // Uses default sizes if none are saved in options
     */
    public function get_font_clamp_variable_sizes()
    {
        static $cached_sizes = null;
        if ($cached_sizes === null) {
            $cached_sizes = get_option(self::OPTION_VARIABLE_SIZES, $this->default_variable_sizes);
        }
        return $cached_sizes;
    }

    /**
     * Get font clamp tag sizes with caching
     * // Caches sizes after first retrieval to optimize performance
     * // Uses default sizes if none are saved in options
     */
    public function get_font_clamp_tag_sizes()
    {
        static $cached_sizes = null;
        if ($cached_sizes === null) {
            $cached_sizes = get_option(self::OPTION_TAG_SIZES, $this->default_tag_sizes);
        }
        return $cached_sizes;
    }

    /**
     * Get font clamp Tailwind sizes with caching
     * // Caches sizes after first retrieval to optimize performance
     * // Uses default sizes if none are saved in options
     */
    public function get_font_clamp_tailwind_sizes()
    {
        static $cached_sizes = null;
        if ($cached_sizes === null) {
            $cached_sizes = get_option(self::OPTION_TAILWIND_SIZES, $this->default_tailwind_sizes);
        }
        return $cached_sizes;
    }

    // ========================================================================
    // MAIN ADMIN PAGE RENDERER
    // ========================================================================

    /**
     * Main Admin Page Renderer - Complete Interface
     */
    public function render_admin_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'fluid-font-forge'));
        }

        $data = $this->prepare_admin_page_data();
        $html_output = $this->get_complete_interface($data);
        echo $html_output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Prepare all data needed for admin page rendering
     * 
     * @return array Complete data array for interface rendering
     */
    private function prepare_admin_page_data()
    {
        return [
            'settings' => $this->get_font_clamp_settings(),
            'classSizes' => $this->get_font_clamp_class_sizes(),
            'variableSizes' => $this->get_font_clamp_variable_sizes(),
            'tagSizes' => $this->get_font_clamp_tag_sizes(),
            'tailwindSizes' => $this->get_font_clamp_tailwind_sizes()
        ];
    }

    /**
     * Complete interface HTML     * 
     * @param array $data Combined data for settings and sizes
     * @return string Complete HTML for the admin interface
     * Uses output buffering to capture and return the complete HTML
     * Includes sections for header, about, loading state, main panel, and how-to-use
     * Ensures consistent styling and structure throughout the interface
     */
    private function get_complete_interface($data)
    {
        $settings = $data['settings'];
        $class_sizes = $data['classSizes'];
        $variable_sizes = $data['variableSizes'];
        $tag_sizes = $data['tagSizes'];

        ob_start();
?>
        <div class="wrap" style="background: var(--clr-page-bg); padding: 20px; min-height: 100vh;">
            <div class="fcc-header-section">
                <h1 class="text-2xl font-bold mb-4">Fluid Font Forge (<?php echo esc_html(FLUID_FONT_FORGE_VERSION); ?>)</h1><br>

                <!-- About Fluid Font Forge Panel -->
                <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/about-panel.php'; ?>
                <!-- Loading State -->
                <div id="fcc-loading-screen" class="fcc-loading-screen">
                    <div class="fcc-loading-content">
                        <div class="fcc-loading-spinner"></div>
                        <h2>Fluid Font Forge<br><br>Loading...</h2>
                        <p>Initializing enhanced interface and advanced features...</p>
                        <div class="fcc-loading-progress">
                            <div class="fcc-progress-bar"></div>
                        </div>
                    </div>
                </div>

                <!-- Main Section -->
                <div class="font-clamp-container" id="fcc-main-container">
                    <div style="padding: 20px;">

                        <!-- How to Use Panel Template -->
                        <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/how-to-use-panel.php'; ?>

                        <!-- Controls -->
                        <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/header-controls.php'; ?>

                        <!-- Settings and Data Table - Side by Side -->
                        <div class="fcc-main-grid">
                            <!-- Settings Panel Template -->
                            <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/settings-panel.php'; ?>
                            <!-- Data Table Panel Template -->
                            <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/data-table-panel.php'; ?>
                        </div>
                    </div>
                </div>

                <!-- Sample Panel Template -->
                <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/sample-panel.php'; ?>

                <!-- Preview Section Template -->
                <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/preview-section.php'; ?>

                <!-- Selected CSS Template -->
                <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/selected-css-panel.php'; ?>

                <!-- Generated CSS Template -->
                <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/generated-css-panel.php'; ?>
            </div>
        </div>
<?php
        return ob_get_clean();
    }

    // ========================================================================
    // UNIFIED ASSET RENDERING
    // ========================================================================

    /**
     * Check if we're on the plugin page
     */
    private function is_font_clamp_page()
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Admin page check, no form processing
        return isset($_GET['page']) && sanitize_text_field(wp_unslash($_GET['page'])) === self::PLUGIN_SLUG;
    }

    // ========================================================================
    // AJAX HANDLERS
    // ========================================================================

    /**
     * Save font sizes via AJAX
     *
     * Handles AJAX requests to save font size data for specific tabs.
     * Validates permissions, sanitizes data, and updates appropriate options.
     *
     * @since 4.1.0
     * @return void Outputs JSON response and exits
     */
    public function save_sizes()
    {
        try {
            // Verify request method and nonce
            if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
                wp_send_json_error(['message' => 'Invalid request method']);
                return;
            }

            // Verify nonce with more specific action
            if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), self::NONCE_ACTION)) {
                wp_die(esc_html__('Security verification failed. Please refresh the page and try again.', 'fluid-font-forge'));
            }

            // Verify user permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(['message' => 'Insufficient permissions']);
                return;
            }

            // Validate and sanitize input data
            $active_tab = isset($_POST['activeTab']) ? sanitize_text_field(wp_unslash($_POST['activeTab'])) : 'class';
            if (!in_array($active_tab, ['class', 'vars', 'tag', 'tailwind'])) {
                wp_send_json_error(['message' => 'Invalid tab type']);
                return;
            }

            $sizes_json = sanitize_textarea_field(wp_unslash($_POST['sizes'] ?? ''));
            if (empty($sizes_json)) {
                wp_send_json_error(['message' => 'No sizes data provided']);
                return;
            }

            $sizes = json_decode($sizes_json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                wp_send_json_error(['message' => 'Invalid JSON data provided']);
                return;
            }

            // Validate sizes array
            if (!is_array($sizes)) {
                wp_send_json_error(['message' => 'Sizes must be an array']);
                return;
            }

            // Save to appropriate option based on tab
            switch ($active_tab) {
                case 'class':
                    $result = update_option(self::OPTION_CLASS_SIZES, $sizes);
                    break;
                case 'vars':
                    $result = update_option(self::OPTION_VARIABLE_SIZES, $sizes);
                    break;
                case 'tag':
                    $result = update_option(self::OPTION_TAG_SIZES, $sizes);
                    break;
                case 'tailwind':
                    $result = update_option(FLUID_FONT_FORGE_OPTION_TAILWIND_SIZES, $sizes);
                    break;
                default:
                    wp_send_json_error(['message' => 'Invalid tab type']);
                    return;
            }

            // Clear cached data and transients
            wp_cache_delete(self::OPTION_CLASS_SIZES, 'options');
            wp_cache_delete(self::OPTION_VARIABLE_SIZES, 'options');
            wp_cache_delete(self::OPTION_TAG_SIZES, 'options');
            wp_cache_delete(FLUID_FONT_FORGE_OPTION_TAILWIND_SIZES, 'options');

            // Clear related transients
            delete_transient('fluid_font_forge_defaults_' . FLUID_FONT_FORGE_VERSION);

            wp_send_json_success([
                'message' => 'Sizes saved successfully',
                'saved' => $result,
                'count' => count($sizes),
                'activeTab' => $active_tab
            ]);
        } catch (Exception $e) {
            wp_send_json_error(['message' => 'Save failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Save plugin settings via AJAX
     *
     * Handles AJAX requests to save plugin settings and size data.
     * Validates user permissions, sanitizes input data, and updates options.
     *
     * @since 4.1.0
     * @return void Outputs JSON response and exits
     */
    public function save_settings()
    {
        try {
            // Update nonce verification to use correct action
            if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'fluid_font_nonce')) {
                wp_send_json_error(['message' => 'Security check failed']);
                return;
            }

            // Verify user permissions
            if (!current_user_can('manage_options')) {
                wp_send_json_error(['message' => 'Insufficient permissions']);
                return;
            }

            // Decode and validate settings data  
            $settings_json = sanitize_textarea_field(wp_unslash($_POST['settings'] ?? ''));

            $settings = json_decode($settings_json, true);

            // Sanitize individual setting values
            if (is_array($settings)) {
                $settings['minRootSize'] = absint($settings['minRootSize'] ?? 16);
                $settings['maxRootSize'] = absint($settings['maxRootSize'] ?? 20);
                $settings['minViewport'] = absint($settings['minViewport'] ?? 375);
                $settings['maxViewport'] = absint($settings['maxViewport'] ?? 1620);
                $settings['unitType'] = sanitize_key($settings['unitType'] ?? 'px');
                $settings['activeTab'] = sanitize_key($settings['activeTab'] ?? 'class');

                // Validate and sanitize preview font URL
                $preview_url = esc_url_raw($settings['previewFontUrl'] ?? '');
                // Only accept valid HTTP/HTTPS URLs or empty string
                if (!empty($preview_url) && !filter_var($preview_url, FILTER_VALIDATE_URL)) {
                    $preview_url = ''; // Invalid URL, use empty string
                }
                // Additional check: Only allow http/https protocols
                if (!empty($preview_url) && !preg_match('/^https?:\/\//i', $preview_url)) {
                    $preview_url = ''; // Non-HTTP protocol, reject
                }
                $settings['previewFontUrl'] = $preview_url;
                $settings['autosaveEnabled'] = (bool) ($settings['autosaveEnabled'] ?? true);
            }
            if (json_last_error() !== JSON_ERROR_NONE) {
                wp_send_json_error(['message' => 'Invalid JSON data provided']);
                return;
            }

            if (!is_array($settings)) {
                wp_send_json_error(['message' => 'Settings must be an array']);
                return;
            }

            // Decode and validate sizes data
            $sizes_json = sanitize_textarea_field(wp_unslash($_POST['sizes'] ?? ''));
            $sizes = json_decode($sizes_json, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                wp_send_json_error(['message' => 'Invalid JSON data provided']);
                return;
            }

            if (!is_array($sizes)) {
                wp_send_json_error(['message' => 'Sizes must be an array']);
                return;
            }

            // Save settings
            $result1 = update_option(self::OPTION_SETTINGS, $settings);
            $result2 = update_option(self::OPTION_CLASS_SIZES, $sizes['classSizes'] ?? []);
            $result3 = update_option(self::OPTION_VARIABLE_SIZES, $sizes['variableSizes'] ?? []);
            $result4 = update_option(self::OPTION_TAG_SIZES, $sizes['tagSizes'] ?? []);

            // Clear cached data
            wp_cache_delete(self::OPTION_SETTINGS, 'options');
            wp_cache_delete(self::OPTION_CLASS_SIZES, 'options');
            wp_cache_delete(self::OPTION_VARIABLE_SIZES, 'options');
            wp_cache_delete(self::OPTION_TAG_SIZES, 'options');

            wp_send_json_success([
                'message' => 'All data saved to database successfully',
                'saved_settings' => $result1,
                'saved_sizes' => $result2 && $result3 && $result4,
                'timestamp' => current_time('timestamp')
            ]);
        } catch (Exception $e) {
            wp_send_json_error([
                'message' => 'Save failed: ' . $e->getMessage(),
                'error_code' => 'SAVE_SETTINGS_FAILED',
                'timestamp' => current_time('timestamp')
            ]);
        }
    }
}
