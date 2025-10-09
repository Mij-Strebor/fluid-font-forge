<?php

/**
 * Sample Panel Template - Interactive Viewport Preview
 * 
 * The sample panel provides an interactive preview of how the fluid font scales
 * f
 * 
 * @package FluidFontForge
 * @since 4.2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- Sample Text Preview Panel -->

<div class="fcc-preview-enhanced" style="clear: both; margin: 20px 0;">

    <button class="fcc-info-toggle expanded" data-toggle-target="sample-text-content" style="width: 100%; text-align: left; background: transparent; border: none; border-bottom: 2px solid var(--clr-secondary); padding-bottom: var(--jimr-space-3);     
    margin-bottom: var(--jimr-space-5); cursor: pointer;">
        <span style="color: var(--clr-primary) !important; font-family: 'Georgia', serif !important; font-size: var(--jimr-font-2xl) !important; font-weight: 700 !important;">>📝 Sample Text</span>
        <span class="fcc-toggle-icon" style="color: var(--clr-primary) !important; float: right;">▼</span>
    </button>

    <div class="fcc-info-content expanded" id="sample-text-content">
        <div class="fcc-sample-display" style="background: white; border-radius: 8px; padding: 20px; border: 2px solid var(--clr-secondary); margin-bottom: 20px; box-shadow: inset 0 2px 4px var(--clr-shadow); min-height: 220px; overflow: hidden;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; height: 100%; align-items: start;">
                <!-- Left Column: Titles -->
                <div style="overflow: hidden; background: rgba(59, 130, 246, 0.03); padding: 16px; border-radius: 6px;">
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 12px;">
                        <span id="titles-header-label" style="font-weight: 600; color: var(--clr-secondary);">Titles</span>
                        <span id="titles-size-display" style="font-size: 14px; color: var(--clr-primary); font-weight: 600;">--</span>
                    </div>
                    <div id="sample-header" style="font-family: var(--preview-font, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif); transition: font-size 0.2s ease; color: var(--clr-txt); font-weight: 700; overflow: hidden; text-align: left; margin: 0; padding: 0;">
                        A wizard's job is to vex chumps quickly in fog.<br>
                        ABCDEFGHIJKLMNOPQRSTUVWXYZ<br>
                        abcdefghijklmnopqrstuvwxyz
                    </div>
                </div>

                <!-- Right Column: Text -->
                <div style="overflow: hidden; background: rgba(59, 130, 246, 0.03); padding: 16px; border-radius: 6px;">
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 12px;">
                        <span id="text-header-label" style="font-weight: 600; color: var(--clr-secondary);">Text</span>
                        <span id="text-size-display" style="font-size: 14px; color: var(--clr-primary); font-weight: 600;">--</span>
                    </div>
                    <div id="sample-text" style="font-family: var(--preview-font, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif); transition: font-size 0.2s ease; color: var(--clr-txt); font-weight: 400; overflow: hidden; text-align: left; margin: 0; padding: 0;">
                        A wizard's job is to vex chumps quickly in fog.<br>
                        ABCDEFGHIJKLMNOPQRSTUVWXYZ<br>
                        abcdefghijklmnopqrstuvwxyz
                    </div>
                </div>
            </div>
        </div>

        <!-- Base Size Controls -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 20px; max-width: 600px; margin-left: auto; margin-right: auto;">
            <!-- Titles Base Dropdown -->
            <div>
                <label for="titles-base-value" style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--clr-txt);">Titles base:</label>
                <select id="titles-base-value" class="component-select" style="width: 100%; height: 40px;">
                    <option>Loading...</option>
                </select>
            </div>

            <!-- Text Base Dropdown -->
            <div>
                <label for="text-base-value" style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--clr-txt);">Text base:</label>
                <select id="text-base-value" class="component-select" style="width: 100%; height: 40px;">
                    <option>Loading...</option>
                </select>
            </div>
        </div>

        <!-- Viewport Size Slider -->
        <div class="fcc-viewport-slider-container" style="margin-bottom: 16px; display: flex; flex-direction: column; align-items: center;">
            <div style="display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 12px; width: 100%;">
                <span style="font-size: 12px; color: var(--clr-txt); font-weight: 500;">Viewport Size:</span>
                <span id="viewport-display" style="font-size: 14px; color: var(--clr-primary); font-weight: 600;">
                    <?php echo esc_html(($settings['minViewport'] + $settings['maxViewport']) / 2); ?>px
                </span>
                <span style="font-size: 12px; color: var(--clr-txt); font-weight: 500;">•</span>
                <span id="device-type-display" style="font-size: 12px; color: var(--clr-secondary); font-weight: 600; font-style: italic;">
                    Desktop
                </span>
            </div>
            <div style="position: relative; width: 25%;">
                <input type="range"
                    id="viewport-slider"
                    min="<?php echo esc_attr($settings['minViewport']); ?>"
                    max="<?php echo esc_attr($settings['maxViewport']); ?>"
                    value="<?php echo esc_attr($settings['minViewport'] + (($settings['maxViewport'] - $settings['minViewport']) * 0.3)); ?>"
                    style="width: 100%; height: 6px; border-radius: 3px; background: linear-gradient(to right, var(--clr-secondary), var(--clr-accent), var(--clr-secondary)); outline: none; -webkit-appearance: none; appearance: none;"
                    data-tooltip="Drag to see how your font scales across different screen sizes">
                <div style="display: flex; justify-content: space-between; margin-top: 4px; font-size: 11px; color: var(--clr-txt);">
                    <span><?php echo esc_html($settings['minViewport']); ?>px</span>
                    <span><?php echo esc_html($settings['maxViewport']); ?>px</span>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div style="display: flex; justify-content: center;">
            <div style="background: rgba(60, 32, 23, 0.1); padding: 12px 16px; border-radius: 6px; border-left: 4px solid var(--clr-accent); text-align: center; width: 70%;">
                <p style="margin: 0; font-size: 13px; color: var(--clr-txt); line-height: 1.4;">
                    <strong>Interactive Preview:</strong> The <strong>Titles base</strong> and <strong>Text base</strong> dropdowns let you select which size from your data table controls each preview. Choose larger sizes for titles, smaller for body text. The viewport slider shows real-time interpolation between your minimum and maximum settings as you drag across different screen sizes.
                </p>
            </div>
        </div>
    </div>
</div>