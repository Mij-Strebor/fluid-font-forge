<?php

/**
 * Data Table Panel Template - Font Size Management
 * 
 * @package FluidFontForge
 * @since 4.2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div>
    <div class="fcc-panel" id="sizes-table-container" style="padding: 24px !important;">
        <h2 style="margin-bottom: 12px;" id="table-title">Font Size Classes</h2>

        <!-- Base Value and Action Buttons Row -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <!-- Left: Base Value Combo Box -->
            <div style="display: flex; align-items: center; gap: 8px;">
                <label class="component-label" for="base-value" style="margin-bottom: 0; white-space: nowrap;">Base</label>
                <select id="base-value" class="component-select" style="width: 120px; height: 32px;"
                    aria-label="Base reference size - used for calculating all other font sizes in the scale, typically your body text size"
                    data-tooltip="Reference size used for calculating other sizes - this will be your body text size">
                    <?php
                    // Populate based on current tab
                    if ($settings['activeTab'] === 'class') {
                        $fluid_font_forge_current_sizes = $class_sizes;
                        $fluid_font_forge_property_name = 'className';
                        $fluid_font_forge_selected_id = $settings['selectedClassSizeId'];
                    } elseif ($settings['activeTab'] === 'vars') {
                        $fluid_font_forge_current_sizes = $variable_sizes;
                        $fluid_font_forge_property_name = 'variableName';
                        $fluid_font_forge_selected_id = $settings['selectedVariableSizeId'];
                    } elseif ($settings['activeTab'] === 'tailwind') {
                        $fluid_font_forge_current_sizes = $this->get_font_clamp_tailwind_sizes();
                        $fluid_font_forge_property_name = 'tailwindName';
                        $fluid_font_forge_selected_id = 5; // default to 'base'
                    } else {
                        $fluid_font_forge_current_sizes = $tag_sizes;
                        $fluid_font_forge_property_name = 'tagName';
                        $fluid_font_forge_selected_id = $settings['selectedTagSizeId'];
                    }
                    foreach ($fluid_font_forge_current_sizes as $fluid_font_forge_size) {
                        $fluid_font_forge_selected = $fluid_font_forge_size['id'] == $fluid_font_forge_selected_id ? 'selected' : '';
                        echo '<option value="' . esc_attr($fluid_font_forge_size['id']) . '" ' . ($fluid_font_forge_selected ? 'selected="selected"' : '') . '>' . esc_html($fluid_font_forge_size[$fluid_font_forge_property_name]) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <!-- Right: Action Buttons -->
            <div class="fcc-table-buttons" id="table-action-buttons">
                <div style="color: var(--clr-secondary); font-size: 12px; font-style: italic;">Loading...</div>
            </div>
        </div>

        <div id="sizes-table-wrapper">
        </div>
    </div>
</div>