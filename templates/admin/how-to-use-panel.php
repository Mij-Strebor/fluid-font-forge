<?php

/**
 * How to Use Panel Template
 * 
 * @package FluidFontForge
 * @since 4.2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- How to Use Panel -->
<?php
$fff_is_expanded = isset($settings['howToUseExpanded']) ? $settings['howToUseExpanded'] : true;
$fff_expanded_class = $fff_is_expanded ? 'expanded' : '';
?>
<div class="fcc-info-toggle-section">
    <button class="fcc-info-toggle <?php echo esc_attr($fff_expanded_class); ?>" data-toggle-target="info-content">
        <span style="color: #FAF9F6 !important;">How to Use Fluid Font Forge</span>
        <span class="fcc-toggle-icon" style="color: #FAF9F6 !important;">▼</span>
    </button>
    <div class="fcc-info-content <?php echo esc_attr($fff_expanded_class); ?>" id="info-content">
        <div style="color: var(--clr-txt); font-size: 16px; line-height: 1.6;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 20px;">
                <div>
                    <h4 style="color: var(--clr-secondary); font-size: 15px; font-weight: 600; margin: 0 0 8px 0;">1. Configure Settings</h4>
                    <p style="margin: 0; font-size: 16px; line-height: 1.5;">Set your font units, viewport range, and scaling ratios. Choose a base value that represents your base root size font size.</p>
                </div>
                <div>
                    <h4 style="color: var(--clr-secondary); font-size: 15px; font-weight: 600; margin: 0 0 8px 0;">2. Manage Font Sizes</h4>
                    <p style="margin: 0; font-size: 16px; line-height: 1.5;">Use the enhanced table to add, edit, delete, or reorder your font sizes. Drag rows to reorder them in the table.</p>
                </div>
                <div>
                    <h4 style="color: var(--clr-secondary); font-size: 15px; font-weight: 600; margin: 0 0 8px 0;">3. Preview Results</h4>
                    <p style="margin: 0; font-size: 16px; line-height: 1.5;">Use the enhanced preview with controls to see how your fonts will look at different screen sizes. The displays are at scale at the ends of your entered Min Width and Max Width.</p>
                </div>
                <div>
                    <h4 style="color: var(--clr-secondary); font-size: 15px; font-weight: 600; margin: 0 0 8px 0;">4. Copy CSS</h4>
                    <p style="margin: 0; font-size: 16px; line-height: 1.5;">Generate clamp() CSS functions ready to use in your projects. Available as classes, variables, or tag styles with enhanced copy functionality.</p>
                </div>
            </div>

            <div style="background: rgba(60, 32, 23, 0.1); padding: 12px 16px; border-radius: 6px; border-left: 4px solid var(--clr-accent); margin: 16px 0 0 0; max-width: 78%; margin-left: auto; margin-right: auto;">
                <h4 style="color: var(--clr-txt); font-size: 16px; font-weight: 600; margin: 0 0 6px 0; text-align: center;">💡 Pro Tip</h4>
                <p style="margin: 0; font-size: 16px; color: var(--clr-txt); text-align: left; padding: 0 20px;">Use Preview Font to test with your actual web fonts and enjoy the enhanced interactive experience with smooth animations and professional styling.</p>
            </div>
        </div>
    </div>
</div>