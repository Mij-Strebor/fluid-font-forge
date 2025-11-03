<?php

/**
 * Settings Panel Template - Font Configuration Controls
 * 
 * @package FluidFontForge
 * @since 4.2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- Column 1: Settings Panel -->
<div>
    <div class="fcc-panel" style="margin-bottom: 8px; padding: 24px !important;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h2 class="settings-title" style="margin: 0;">Settings</h2>
            <button id="reset-settings-btn" class="fcc-btn" data-tooltip="Reset all settings to default values">
                reset
            </button>
        </div>

        <!-- Font Units Selector -->
        <div class="font-units-section">
            <label class="font-units-label">Select Font Units to Use:</label>
            <div class="font-units-buttons">
                <button id="px-tab" class="unit-button <?php echo $settings['unitType'] === 'px' ? 'active' : ''; ?>" data-unit="px"
                    aria-label="Use pixel units for font sizes - more predictable but less accessible"
                    aria-pressed="<?php echo $settings['unitType'] === 'px' ? 'true' : 'false'; ?>"
                    data-tooltip="Use pixel units for font sizes - more predictable but less accessible">PX</button>
                <button id="rem-tab" class="unit-button <?php echo $settings['unitType'] === 'rem' ? 'active' : ''; ?>" data-unit="rem"
                    aria-label="Use rem units for font sizes - scales with user's browser settings"
                    aria-pressed="<?php echo $settings['unitType'] === 'rem' ? 'true' : 'false'; ?>"
                    data-tooltip="Use rem units for font sizes - scales with user's browser settings">REM</button>
            </div>
        </div>
        <p class="divider">What is the base font size at the viewport limits and the viewport range?</p>
        <!-- Row 1: Min Root and Min Width -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px;">
            <div class="grid-item">
                <label class="component-label" for="min-root-size">Min Viewport Font Size (px)</label>
                <input type="number" id="min-root-size" value="<?php echo esc_attr($settings['minRootSize'] ?? \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_MIN_ROOT_SIZE); ?>"
                    class="component-input" style="width: 100%;"
                    min="<?php echo esc_attr(\JimRWeb\FluidFontForge\FluidFontForge::MIN_ROOT_SIZE_RANGE[0]); ?>"
                    max="<?php echo esc_attr(\JimRWeb\FluidFontForge\FluidFontForge::MIN_ROOT_SIZE_RANGE[1]); ?>"
                    step="1"
                    aria-label="Minimum root font size in pixels - base font size at minimum viewport width"
                    data-tooltip="Base font size at minimum viewport width">
            </div>
            <div class="grid-item">
                <label class="component-label" for="min-viewport">Min Viewport Width (px)</label>
                <input type="number" id="min-viewport" value="<?php echo esc_attr($settings['minViewport']); ?>"
                    class="component-input" style="width: 100%;"
                    min="<?php echo esc_attr(\JimRWeb\FluidFontForge\FluidFontForge::VIEWPORT_RANGE[0]); ?>"
                    max="<?php echo esc_attr(\JimRWeb\FluidFontForge\FluidFontForge::VIEWPORT_RANGE[1]); ?>"
                    step="1"
                    aria-label="Minimum viewport width in pixels - screen width where minimum font size applies"
                    data-tooltip="Screen width where minimum font size applies">
            </div>
        </div>

        <!-- Row 2: Max Root and Max Width -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px;">
            <div class="grid-item">
                <label class="component-label" for="max-root-size">Max Viewport Font Size (px)</label>
                <input type="number" id="max-root-size" value="<?php echo esc_attr($settings['maxRootSize'] ?? \JimRWeb\FluidFontForge\FluidFontForge::DEFAULT_MAX_ROOT_SIZE); ?>"
                    class="component-input" style="width: 100%;"
                    min="<?php echo esc_attr(\JimRWeb\FluidFontForge\FluidFontForge::MIN_ROOT_SIZE_RANGE[0]); ?>"
                    max="<?php echo esc_attr(\JimRWeb\FluidFontForge\FluidFontForge::MIN_ROOT_SIZE_RANGE[1]); ?>"
                    step="1"
                    aria-label="Maximum root font size in pixels - base font size at maximum viewport width"
                    data-tooltip="Base font size at maximum viewport width">
            </div>
            <div class="grid-item">
                <label class="component-label" for="max-viewport">Max Viewport Width (px)</label>
                <input type="number" id="max-viewport" value="<?php echo esc_attr($settings['maxViewport']); ?>"
                    class="component-input" style="width: 100%;"
                    min="<?php echo esc_attr(\JimRWeb\FluidFontForge\FluidFontForge::VIEWPORT_RANGE[0]); ?>"
                    max="<?php echo esc_attr(\JimRWeb\FluidFontForge\FluidFontForge::VIEWPORT_RANGE[1]); ?>"
                    step="1"
                    aria-label="Maximum viewport width in pixels - screen width where maximum font size applies"
                    data-tooltip="Screen width where maximum font size applies">
            </div>
        </div>
        <p class="divider">How should your font scale? Set the ratio at both viewport limits.</p>

        <!-- Row 3: Min Scale -->
        <div style="display: grid; grid-template-columns: 1fr; gap: 16px; margin-top: 16px;">
            <div class="grid-item">
                <label class="component-label" for="min-scale">Min Viewport Font Scaling</label>
                <select id="min-scale" class="component-select" style="width: 100%;"
                    aria-label="Minimum scale ratio for typography on smaller screens - controls size differences between font levels"
                    data-tooltip="Typography scale ratio for smaller screens - how much size difference between font levels">
                    <option value="1.067" <?php selected($settings['minScale'], '1.067'); ?>>1.067 Minor Second</option>
                    <option value="1.125" <?php selected($settings['minScale'], '1.125'); ?>>1.125 Major Second</option>
                    <option value="1.200" <?php selected($settings['minScale'], '1.200'); ?>>1.200 Minor Third</option>
                    <option value="1.250" <?php selected($settings['minScale'], '1.250'); ?>>1.250 Major Third</option>
                    <option value="1.333" <?php selected($settings['minScale'], '1.333'); ?>>1.333 Perfect Fourth</option>
                </select>
            </div>
        </div>

        <!-- Row 4: Max Scale -->
        <div style="display: grid; grid-template-columns: 1fr; gap: 16px; margin-top: 16px;">
            <div class="grid-item">
                <label class="component-label" for="max-scale">Max Viewport Font Scaling</label>
                <select id="max-scale" class="component-select" style="width: 100%;"
                    aria-label="Maximum scale ratio for typography on larger screens - controls how dramatic size differences are on big screens"
                    data-tooltip="Typography scale ratio for larger screens - how dramatic the size differences should be on big screens">
                    <option value="1.067" <?php selected($settings['maxScale'], '1.067'); ?>>1.067 Minor Second</option>
                    <option value="1.125" <?php selected($settings['maxScale'], '1.125'); ?>>1.125 Major Second</option>
                    <option value="1.200" <?php selected($settings['maxScale'], '1.200'); ?>>1.200 Minor Third</option>
                    <option value="1.250" <?php selected($settings['maxScale'], '1.250'); ?>>1.250 Major Third</option>
                    <option value="1.333" <?php selected($settings['maxScale'], '1.333'); ?>>1.333 Perfect Fourth</option>
                </select>
            </div>
        </div>
    </div>
</div>