<?php

/**
 * Fluid Font Forge - Default Data Factory
 *
 * Single source of truth for all default data used throughout the plugin.
 * Eliminates duplication between PHP and JavaScript by providing centralized defaults.
 *
 * @package FluidFontForge
 * @version 5.0.0
 * @since 4.2.0
 */

namespace JimRWeb\FluidFontForge;

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Default Data Factory Class
 *
 * Provides all default configurations for settings, sizes, and constants.
 * Used by both PHP backend and JavaScript frontend for consistency.
 * All methods are static for easy access without instantiation.
 *
 * @since 4.2.0
 */
class DefaultDataFactory
{
    /* ==========================================================================
       AGGREGATE DATA METHODS
       ========================================================================== */

    /**
     * Get all default data in a single call
     *
     * Returns complete default configuration including settings, all size types,
     * and constants. Primary method for retrieving full plugin defaults.
     *
     * @since 4.2.0
     * @return array Complete default configuration with keys: settings, classSizes,
     *               variableSizes, tagSizes, tailwindSizes, constants
     */
    public static function getAllDefaults()
    {
        return array(
            'settings' => self::getDefaultSettings(),
            'classSizes' => self::getDefaultClassSizes(),
            'variableSizes' => self::getDefaultVariableSizes(),
            'tagSizes' => self::getDefaultTagSizes(),
            'tailwindSizes' => self::getDefaultTailwindSizes(),
            'constants' => self::getConstants()
        );
    }

    /* ==========================================================================
       SETTINGS CONFIGURATION
       ========================================================================== */

    /**
     * Get default settings configuration
     *
     * Provides initial settings for viewport ranges, root font sizes, scales,
     * unit types, and active selections. These defaults ensure the plugin
     * works immediately upon activation.
     *
     * @since 4.2.0
     * @return array Default settings with viewport, root size, scale, unit type,
     *               selected IDs, active tab, and autosave configuration
     */
    public static function getDefaultSettings()
    {
        return [
            'minRootSize' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_MIN_ROOT_SIZE,
            'maxRootSize' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_MAX_ROOT_SIZE,
            'minViewport' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_MIN_VIEWPORT,
            'maxViewport' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_MAX_VIEWPORT,
            'unitType' => 'px',
            'selectedClassSizeId' => 5,
            'selectedVariableSizeId' => 5,
            'selectedTagSizeId' => 7, // 'p' tag
            'activeTab' => 'class',
            'previewFontUrl' => '',
            'minScale' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_MIN_SCALE,
            'maxScale' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_MAX_SCALE,
            'autosaveEnabled' => false,
            'classBaseValue' => 'medium',
            'varsBaseValue' => '--fs-md',
            'tagBaseValue' => 'p',
            // Panel persistence states - default all panels to expanded
            'aboutExpanded' => true,
            'howToUseExpanded' => true,
            'sampleTextExpanded' => true,
            'fontScaleExpanded' => true
        ];
    }

    /* ==========================================================================
       SIZE TYPE DEFAULTS
       ========================================================================== */

    /**
     * Get default class sizes
     *
     * Provides default font size configurations for CSS class-based typography.
     * Eight sizes from xxxlarge to xxsmall with appropriate line heights.
     *
     * @since 4.2.0
     * @return array Array of size objects with id, className, and lineHeight properties
     */
    public static function getDefaultClassSizes()
    {
        return array(
            array('id' => 1, 'className' => 'xxxlarge', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_HEADING_LINE_HEIGHT),
            array('id' => 2, 'className' => 'xxlarge', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_HEADING_LINE_HEIGHT),
            array('id' => 3, 'className' => 'xlarge', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_HEADING_LINE_HEIGHT),
            array('id' => 4, 'className' => 'large', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT),
            array('id' => 5, 'className' => 'medium', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT),
            array('id' => 6, 'className' => 'small', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT),
            array('id' => 7, 'className' => 'xsmall', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT),
            array('id' => 8, 'className' => 'xxsmall', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT)
        );
    }

    /**
     * Get default CSS variable sizes
     *
     * Provides default font size configurations for CSS custom property-based
     * typography. Eight sizes from --fs-xxxl to --fs-xxs with appropriate line heights.
     *
     * @since 4.2.0
     * @return array Array of size objects with id, variableName, and lineHeight properties
     */
    public static function getDefaultVariableSizes()
    {
        return array(
            array('id' => 1, 'variableName' => '--fs-xxxl', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_HEADING_LINE_HEIGHT),
            array('id' => 2, 'variableName' => '--fs-xxl', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_HEADING_LINE_HEIGHT),
            array('id' => 3, 'variableName' => '--fs-xl', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_HEADING_LINE_HEIGHT),
            array('id' => 4, 'variableName' => '--fs-lg', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT),
            array('id' => 5, 'variableName' => '--fs-md', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT),
            array('id' => 6, 'variableName' => '--fs-sm', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT),
            array('id' => 7, 'variableName' => '--fs-xs', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT),
            array('id' => 8, 'variableName' => '--fs-xxs', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT)
        );
    }

    /**
     * Get default HTML tag sizes
     *
     * Provides default font size configurations for HTML tag-based typography.
     * Semantic HTML tags from h1 to small with appropriate line heights.
     *
     * @since 4.2.0
     * @return array Array of size objects with id, tagName, and lineHeight properties
     */
    public static function getDefaultTagSizes()
    {
        return array(
            array('id' => 1, 'tagName' => 'h1', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_HEADING_LINE_HEIGHT),
            array('id' => 2, 'tagName' => 'h2', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_HEADING_LINE_HEIGHT),
            array('id' => 3, 'tagName' => 'h3', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_HEADING_LINE_HEIGHT),
            array('id' => 4, 'tagName' => 'h4', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_HEADING_LINE_HEIGHT),
            array('id' => 5, 'tagName' => 'h5', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_HEADING_LINE_HEIGHT),
            array('id' => 6, 'tagName' => 'h6', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_HEADING_LINE_HEIGHT),
            array('id' => 7, 'tagName' => 'p', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT),
            array('id' => 8, 'tagName' => 'small', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT)
        );
    }

    /**
     * Get default Tailwind sizes
     *
     * Provides default font size configurations for Tailwind CSS framework.
     * Eight sizes from 4xl to xs with appropriate line heights.
     *
     * @since 4.2.0
     * @return array Array of size objects with id, tailwindName, and lineHeight properties
     */
    public static function getDefaultTailwindSizes()
    {
        return array(
            array('id' => 1, 'tailwindName' => '4xl', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_HEADING_LINE_HEIGHT),
            array('id' => 2, 'tailwindName' => '3xl', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_HEADING_LINE_HEIGHT),
            array('id' => 3, 'tailwindName' => '2xl', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_HEADING_LINE_HEIGHT),
            array('id' => 4, 'tailwindName' => 'xl', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT),
            array('id' => 5, 'tailwindName' => 'base', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT),
            array('id' => 6, 'tailwindName' => 'lg', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT),
            array('id' => 7, 'tailwindName' => 'sm', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT),
            array('id' => 8, 'tailwindName' => 'xs', 'lineHeight' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT)
        );
    }

    /* ==========================================================================
       CONSTANTS AND CONFIGURATION
       ========================================================================== */

    /**
     * Get all constants for JavaScript access
     *
     * Provides all plugin constants in array format for passing to JavaScript.
     * Ensures consistency between PHP and JavaScript validation and defaults.
     *
     * @since 4.2.0
     * @return array All plugin constants including defaults, ranges, and valid values
     */
    public static function getConstants()
    {
        return array(
            'DEFAULT_MIN_ROOT_SIZE' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_MIN_ROOT_SIZE,
            'DEFAULT_MAX_ROOT_SIZE' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_MAX_ROOT_SIZE,
            'DEFAULT_MIN_VIEWPORT' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_MIN_VIEWPORT,
            'DEFAULT_MAX_VIEWPORT' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_MAX_VIEWPORT,
            'DEFAULT_MIN_SCALE' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_MIN_SCALE,
            'DEFAULT_MAX_SCALE' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_MAX_SCALE,
            'DEFAULT_HEADING_LINE_HEIGHT' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_HEADING_LINE_HEIGHT,
            'DEFAULT_BODY_LINE_HEIGHT' => \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_BODY_LINE_HEIGHT,
            'BROWSER_DEFAULT_FONT_SIZE' => \JimRWeb\FluidFontForge\FluidFontForge::BROWSER_DEFAULT_FONT_SIZE,
            'CSS_UNIT_CONVERSION_BASE' => \JimRWeb\FluidFontForge\FluidFontForge::CSS_UNIT_CONVERSION_BASE,
            'MIN_ROOT_SIZE_RANGE' => \JimRWeb\FluidFontForge\FluidFontForge::MIN_ROOT_SIZE_RANGE,
            'VIEWPORT_RANGE' => \JimRWeb\FluidFontForge\FluidFontForge::VIEWPORT_RANGE,
            'LINE_HEIGHT_RANGE' => \JimRWeb\FluidFontForge\FluidFontForge::LINE_HEIGHT_RANGE,
            'SCALE_RANGE' => \JimRWeb\FluidFontForge\FluidFontForge::SCALE_RANGE,
            'VALID_UNITS' => \JimRWeb\FluidFontForge\FluidFontForge::VALID_UNITS,
            'VALID_TABS' => \JimRWeb\FluidFontForge\FluidFontForge::VALID_TABS
        );
    }

    /* ==========================================================================
       UTILITY METHODS
       ========================================================================== */

    /**
     * Get default sizes for a specific type
     *
     * Convenience method to retrieve default sizes by type string.
     * Simplifies code that needs to dynamically access different size types.
     *
     * @since 4.2.0
     * @param string $type Size type ('class', 'vars', 'tag', 'tailwind')
     * @return array Default sizes for the specified type, empty array if invalid type
     */
    public static function getDefaultSizesByType($type)
    {
        switch ($type) {
            case 'class':
                return self::getDefaultClassSizes();
            case 'vars':
                return self::getDefaultVariableSizes();
            case 'tag':
                return self::getDefaultTagSizes();
            case 'tailwind':
                return self::getDefaultTailwindSizes();
            default:
                return [];
        }
    }

    /**
     * Get property name for a size type
     *
     * Returns the array key name used for the size identifier in each type.
     * Enables dynamic property access across different size type structures.
     *
     * @since 4.2.0
     * @param string $type Size type ('class', 'vars', 'tag', 'tailwind')
     * @return string Property name (e.g., 'className', 'variableName', 'tagName', 'tailwindName')
     *                Returns 'className' as default for invalid types
     */
    public static function getPropertyNameForType($type)
    {
        $propertyMap = [
            'class' => 'className',
            'vars' => 'variableName',
            'tag' => 'tagName',
            'tailwind' => 'tailwindName'
        ];

        return $propertyMap[$type] ?? 'className';
    }

    /**
     * Validate size type
     *
     * Checks if a given type string is a valid size type supported by the plugin.
     * Used for input validation before processing size operations.
     *
     * @since 4.2.0
     * @param string $type Size type to validate
     * @return bool True if valid size type, false otherwise
     */
    public static function isValidSizeType($type)
    {
        return in_array($type, ['class', 'vars', 'tag', 'tailwind']);
    }
}
