<?php

/**
 * Preview Section Template - Font Preview
 * 
 * The preview section displays two side-by-side panels showing how the fluid font scales
 * 
 * @package FluidFontForge
 * @since 4.2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<!--  Font Preview Section -->
<div class="fcc-preview-enhanced" style="clear: both; margin: 20px 0;">

    <button class="fcc-info-toggle expanded" data-toggle-target="font-preview-content" style="width: 100%; text-align: left; background: transparent; border: none; border-bottom: 2px solid var(--clr-secondary); padding-bottom: var(--jimr-space-3);     
    margin-bottom: var(--jimr-space-5); cursor: pointer;">
        <span style="color: var(--clr-primary) !important; font-family: 'Georgia', serif !important; font-size: var(--jimr-font-2xl) !important; font-weight: 700 !important;">👁️ Font Scale</span>
        <span class="fcc-toggle-icon" style="color: var(--clr-primary) !important; float: right;">▼</span>
    </button>

    <div class="fcc-info-content expanded" id="font-preview-content">

        <div class="fcc-preview-grid">
            <div class="fcc-preview-column">
                <div class="fcc-preview-column-header">
                    <h3>Min Size (Small Screens)</h3>
                    <div class="fcc-scale-indicator" id="min-viewport-display"><?php echo esc_html($settings['minViewport']); ?>px</div>
                </div>
                <div id="preview-min-container" style="background: white; border-radius: 8px; padding: 20px; border: 2px solid var(--clr-secondary); min-height: 320px; box-shadow: inset 0 2px 4px var(--clr-shadow); overflow: hidden;">
                    <div style="text-align: center; color: var(--clr-txt); font-style: italic; padding: 60px 20px;">
                        <div class="fcc-loading-spinner" style="width: 25px; height: 25px; margin: 0 auto 10px;"></div>
                        <div>Loading preview...</div>
                    </div>
                </div>
            </div>

            <div class="fcc-preview-column">
                <div class="fcc-preview-column-header">
                    <h3>Max Size (Large Screens)</h3>
                    <div class="fcc-scale-indicator" id="max-viewport-display"><?php echo esc_html($settings['maxViewport']); ?>px</div>
                </div>
                <div id="preview-max-container" style="background: white; border-radius: 8px; padding: 20px; border: 2px solid var(--clr-secondary); min-height: 320px; box-shadow: inset 0 2px 4px var(--clr-shadow); overflow: hidden;">
                    <div style="text-align: center; color: var(--clr-txt); font-style: italic; padding: 60px 20px;">
                        <div class="fcc-loading-spinner" style="width: 25px; height: 25px; margin: 0 auto 10px;"></div>
                        <div>Loading preview...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>