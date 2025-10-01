<?php

/**
 * Header Controls Template - Preview Font and Tabs
 * 
 * @package FluidFontForge
 * @since 4.2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div style="margin: 2rem 0;">
    <!-- Top Row: Preview Font Input and Autosave Status -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <div class="fcc-font-input">
            <label for="preview-font-url" data-tooltip="Load a custom WOFF2 font file to preview your font sizes">Preview Font:</label>
            <input type="text" id="preview-font-url" class="fcc-input" placeholder="Paste WOFF2 font URL" value="<?php echo esc_attr($settings['previewFontUrl']); ?>" style="width: 200px; margin-bottom: 0;" data-tooltip="Enter a WOFF2 font URL to see how your sizes look with that font">
            <span id="font-filename">Default</span>
            <button type="button" id="reset-font-btn" class="fcc-btn" style="margin-left: 8px; padding: 4px 12px; font-size: 12px;" data-tooltip="Clear custom font and return to default system fonts">Reset</button>
        </div>

        <div style="display: flex; align-items: center; gap: 20px;">
            <div class="fcc-autosave-flex">
                <label data-tooltip="Automatically save changes as you make them">
                    <input type="checkbox" id="autosave-toggle" <?php echo $settings['autosaveEnabled'] ? 'checked="checked"' : ''; ?> data-tooltip="Toggle automatic saving of your font settings">
                    <span>Autosave</span>
                </label><button id="save-btn" class="fcc-btn" style="padding: 4px 12px; font-size: 12px;" data-tooltip="Save all current settings and sizes to database">
                    Save
                </button>
                Save
                </button>
                <div id="autosave-status" class="autosave-status idle">
                    <span id="autosave-icon">⚡</span>
                    <span id="autosave-text">Ready</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Large Tabs -->
    <div style="display: flex; justify-content: center;">
        <div class="fcc-tabs" style="width: 100%; max-width: 600px;">
            <button id="class-tab" class="tab-button <?php echo $settings['activeTab'] === 'class' ? 'active' : ''; ?>" style="flex: 1; padding: 12px 24px; border-radius: 6px; font-size: 16px; font-weight: 600;" data-tab="class" data-tooltip="Generate CSS classes like .large, .medium, .small for use in HTML">Class</button>
            <button id="vars-tab" class="tab-button <?php echo $settings['activeTab'] === 'vars' ? 'active' : ''; ?>" style="flex: 1; padding: 12px 24px; border-radius: 6px; font-size: 16px; font-weight: 600;" data-tab="vars" data-tooltip="Generate CSS custom properties like --fs-lg for use with var() in CSS">Variables</button>
            <button id="tailwind-tab" class="tab-button <?php echo $settings['activeTab'] === 'tailwind' ? 'active' : ''; ?>" style="flex: 1; padding: 12px 24px; border-radius: 6px; font-size: 16px; font-weight: 600;" data-tab="tailwind" data-tooltip="Generate Tailwind config fontSize object for direct integration with tailwind.config.js">Tailwind Config</button>
            <button id="tag-tab" class="tab-button <?php echo $settings['activeTab'] === 'tag' ? 'active' : ''; ?>" style="flex: 1; padding: 12px 24px; border-radius: 6px; font-size: 16px; font-weight: 600;" data-tab="tag" data-tooltip="Generate CSS that directly styles HTML tags like h1, h2, p automatically">Tags</button>
        </div>
    </div>
</div>