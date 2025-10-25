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
                        $current_sizes = $class_sizes;
                        $property_name = 'className';
                        $selected_id = $settings['selectedClassSizeId'];
                    } elseif ($settings['activeTab'] === 'vars') {
                        $current_sizes = $variable_sizes;
                        $property_name = 'variableName';
                        $selected_id = $settings['selectedVariableSizeId'];
                    } elseif ($settings['activeTab'] === 'tailwind') {
                        $current_sizes = $this->get_font_clamp_tailwind_sizes();
                        $property_name = 'tailwindName';
                        $selected_id = 5; // default to 'base'
                    } else {
                        $current_sizes = $tag_sizes;
                        $property_name = 'tagName';
                        $selected_id = $settings['selectedTagSizeId'];
                    }
                    foreach ($current_sizes as $size) {
                        $selected = $size['id'] == $selected_id ? 'selected' : '';
                        echo '<option value="' . esc_attr($size['id']) . '" ' . ($selected ? 'selected="selected"' : '') . '>' . esc_html($size[$property_name]) . '</option>';
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