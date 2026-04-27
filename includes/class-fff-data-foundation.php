<?php
/**
 * Data Foundation - Export/Import Support
 *
 * Provides methods for exporting and importing plugin settings,
 * establishing the data architecture needed for future configuration
 * management features.
 *
 * @package    FluidFontForge
 * @subpackage DataFoundation
 * @since      5.3.0
 * @version    5.3.0
 * @author     Jim R Forge
 * @link       https://jimrforge.com
 * @license    GPL-2.0-or-later
 */

namespace JimRForge\FluidFontForge;

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Data Foundation Trait
 *
 * Provides export/import functionality for FluidFontForge main class.
 * Use this trait in the main FluidFontForge class to add data foundation methods.
 *
 * @since 5.3.0
 */
trait DataFoundationTrait
{
    /**
     * Get complete current settings as associative array
     *
     * Returns all plugin settings and size data in a structured format
     * suitable for export, backup, or API consumption. This method serves
     * as the foundation for configuration management features.
     *
     * @since 5.3.0
     * @return array Complete settings with metadata
     */
    public function get_current_settings_export()
    {
        return [
            'settings' => $this->get_font_clamp_settings(),
            'sizes' => [
                'class' => $this->get_font_clamp_class_sizes(),
                'variables' => $this->get_font_clamp_variable_sizes(),
                'tags' => $this->get_font_clamp_tag_sizes(),
                'tailwind' => $this->get_font_clamp_tailwind_sizes()
            ],
            'metadata' => [
                'version' => FLUID_FONT_FORGE_VERSION,
                'timestamp' => time(),
                'wordpress_version' => get_bloginfo('version'),
                'php_version' => PHP_VERSION,
                'export_date' => current_time('mysql')
            ]
        ];
    }

    /**
     * Validate incoming settings array for import
     *
     * Performs comprehensive validation of imported settings data,
     * checking structure, data types, and value ranges. Returns either
     * validated settings or WP_Error with specific error message.
     *
     * @since 5.3.0
     * @param array $settings Settings array to validate
     * @return array|\WP_Error Validated settings or error
     */
    public function validate_settings_import($settings)
    {
        // Structure check
        if (!is_array($settings) || !isset($settings['settings'])) {
            return new \WP_Error('invalid_structure', __('Settings must contain a settings array', 'fluid-font-forge'));
        }

        $validated = [
            'settings' => [],
            'sizes' => [
                'class' => [],
                'variables' => [],
                'tags' => [],
                'tailwind' => []
            ],
            'validation_notes' => []
        ];

        // Settings validation
        $s = $settings['settings'];

        // Min/Max Root Size validation
        $min_root = isset($s['minRootSize']) ? absint($s['minRootSize']) : self::DEFAULT_MIN_ROOT_SIZE;
        $max_root = isset($s['maxRootSize']) ? absint($s['maxRootSize']) : self::DEFAULT_MAX_ROOT_SIZE;

        if ($min_root < self::MIN_ROOT_MIN || $min_root > self::MIN_ROOT_MAX) {
            return new \WP_Error('invalid_min_root', sprintf(
                /* translators: 1: minimum allowed root size in px, 2: maximum allowed root size in px */
                __('Min root size must be %1$d-%2$dpx', 'fluid-font-forge'),
                self::MIN_ROOT_MIN,
                self::MIN_ROOT_MAX
            ));
        }

        if ($max_root < $min_root || $max_root > self::MAX_ROOT_MAX) {
            return new \WP_Error('invalid_max_root', sprintf(
                /* translators: 1: minimum root size in px, 2: maximum allowed root size in px */
                __('Max root size must be between min (%1$dpx) and %2$dpx', 'fluid-font-forge'),
                $min_root,
                self::MAX_ROOT_MAX
            ));
        }

        $validated['settings']['minRootSize'] = $min_root;
        $validated['settings']['maxRootSize'] = $max_root;

        // Viewport validation
        $min_vp = isset($s['minViewport']) ? absint($s['minViewport']) : self::DEFAULT_MIN_VIEWPORT;
        $max_vp = isset($s['maxViewport']) ? absint($s['maxViewport']) : self::DEFAULT_MAX_VIEWPORT;

        if ($min_vp < self::MIN_VIEWPORT_MIN || $min_vp > self::MIN_VIEWPORT_MAX) {
            return new \WP_Error('invalid_min_viewport', sprintf(
                /* translators: 1: minimum allowed viewport in px, 2: maximum allowed viewport in px */
                __('Min viewport must be %1$d-%2$dpx', 'fluid-font-forge'),
                self::MIN_VIEWPORT_MIN,
                self::MIN_VIEWPORT_MAX
            ));
        }

        if ($max_vp <= $min_vp || $max_vp > self::MAX_VIEWPORT_MAX) {
            return new \WP_Error('invalid_max_viewport', sprintf(
                /* translators: 1: minimum viewport in px, 2: maximum allowed viewport in px */
                __('Max viewport must be greater than min (%1$dpx) and max %2$dpx', 'fluid-font-forge'),
                $min_vp,
                self::MAX_VIEWPORT_MAX
            ));
        }

        $validated['settings']['minViewport'] = $min_vp;
        $validated['settings']['maxViewport'] = $max_vp;

        // Scale validation
        $min_scale = isset($s['minScale']) ? floatval($s['minScale']) : self::DEFAULT_MIN_SCALE;
        $max_scale = isset($s['maxScale']) ? floatval($s['maxScale']) : self::DEFAULT_MAX_SCALE;

        if ($min_scale < self::SCALE_RANGE[0] || $min_scale > self::SCALE_RANGE[1]) {
            return new \WP_Error('invalid_min_scale', sprintf(
                /* translators: 1: minimum allowed scale value, 2: maximum allowed scale value */
                __('Min scale must be %1$.1f-%2$.1f', 'fluid-font-forge'),
                self::SCALE_RANGE[0],
                self::SCALE_RANGE[1]
            ));
        }

        if ($max_scale < self::SCALE_RANGE[0] || $max_scale > self::SCALE_RANGE[1]) {
            return new \WP_Error('invalid_max_scale', sprintf(
                /* translators: 1: minimum allowed scale value, 2: maximum allowed scale value */
                __('Max scale must be %1$.1f-%2$.1f', 'fluid-font-forge'),
                self::SCALE_RANGE[0],
                self::SCALE_RANGE[1]
            ));
        }

        $validated['settings']['minScale'] = $min_scale;
        $validated['settings']['maxScale'] = $max_scale;

        // Unit type validation
        $unit_type = isset($s['unitType']) ? sanitize_key($s['unitType']) : 'px';
        if (!in_array($unit_type, self::VALID_UNITS)) {
            $unit_type = 'px';
            $validated['validation_notes'][] = __('Unit type corrected to px', 'fluid-font-forge');
        }
        $validated['settings']['unitType'] = $unit_type;

        // Active tab validation
        $active_tab = isset($s['activeTab']) ? sanitize_key($s['activeTab']) : 'class';
        if (!in_array($active_tab, self::VALID_TABS)) {
            $active_tab = 'class';
            $validated['validation_notes'][] = __('Active tab corrected to class', 'fluid-font-forge');
        }
        $validated['settings']['activeTab'] = $active_tab;

        // Preview font URL validation
        $preview_url = isset($s['previewFontUrl']) ? esc_url_raw($s['previewFontUrl']) : '';
        if (!empty($preview_url) && !filter_var($preview_url, FILTER_VALIDATE_URL)) {
            $preview_url = '';
            $validated['validation_notes'][] = __('Invalid preview font URL removed', 'fluid-font-forge');
        }
        $validated['settings']['previewFontUrl'] = $preview_url;

        // Autosave setting
        $validated['settings']['autosaveEnabled'] = isset($s['autosaveEnabled']) ? (bool) $s['autosaveEnabled'] : false;

        // Sizes validation (if provided)
        if (isset($settings['sizes'])) {
            foreach (['class', 'variables', 'tags', 'tailwind'] as $type) {
                if (isset($settings['sizes'][$type]) && is_array($settings['sizes'][$type])) {
                    $validated['sizes'][$type] = $this->validate_sizes_array($settings['sizes'][$type]);
                }
            }
        }

        return $validated;
    }

    /**
     * Validate sizes array structure
     *
     * Ensures each size entry has required properties and valid values.
     *
     * @since 5.3.0
     * @param array $sizes Array of size objects
     * @return array Validated sizes
     */
    private function validate_sizes_array($sizes)
    {
        if (!is_array($sizes)) {
            return [];
        }

        $validated = [];
        foreach ($sizes as $size) {
            if (!is_array($size)) {
                continue;
            }

            // Validate required fields
            if (!isset($size['name']) || !isset($size['minSize']) || !isset($size['maxSize'])) {
                continue;
            }

            $validated[] = [
                'name' => sanitize_text_field($size['name']),
                'minSize' => floatval($size['minSize']),
                'maxSize' => floatval($size['maxSize']),
                'baseValue' => isset($size['baseValue']) ? intval($size['baseValue']) : 0,
                'clampValue' => isset($size['clampValue']) ? sanitize_text_field($size['clampValue']) : '',
                'skipped' => isset($size['skipped']) ? (bool) $size['skipped'] : false
            ];
        }

        return $validated;
    }

    /**
     * Apply validated settings to WordPress options
     *
     * Updates plugin options with validated import data.
     *
     * @since 5.3.0
     * @param array $validated Validated settings from validate_settings_import()
     * @return bool True on success
     */
    public function apply_imported_settings($validated)
    {
        if (!is_array($validated) || !isset($validated['settings'])) {
            return false;
        }

        // Update main settings
        update_option(self::OPTION_SETTINGS, $validated['settings']);

        // Update sizes if provided
        if (isset($validated['sizes'])) {
            if (!empty($validated['sizes']['class'])) {
                update_option(self::OPTION_CLASS_SIZES, $validated['sizes']['class']);
            }
            if (!empty($validated['sizes']['variables'])) {
                update_option(self::OPTION_VARIABLE_SIZES, $validated['sizes']['variables']);
            }
            if (!empty($validated['sizes']['tags'])) {
                update_option(self::OPTION_TAG_SIZES, $validated['sizes']['tags']);
            }
            if (!empty($validated['sizes']['tailwind'])) {
                update_option(self::OPTION_TAILWIND_SIZES, $validated['sizes']['tailwind']);
            }
        }

        // Clear caches
        wp_cache_delete(self::OPTION_SETTINGS, 'options');
        wp_cache_delete(self::OPTION_CLASS_SIZES, 'options');
        wp_cache_delete(self::OPTION_VARIABLE_SIZES, 'options');
        wp_cache_delete(self::OPTION_TAG_SIZES, 'options');
        wp_cache_delete(self::OPTION_TAILWIND_SIZES, 'options');

        return true;
    }
}
