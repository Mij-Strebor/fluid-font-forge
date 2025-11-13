<?php

/**
 * About Section Template
 * 
 * @package FluidFontForge
 * @since 4.2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<?php
$fff_is_expanded = isset($settings['aboutExpanded']) ? $settings['aboutExpanded'] : true;
$fff_expanded_class = $fff_is_expanded ? 'expanded' : '';
?>
<div class="fcc-info-toggle-section">
    <button class="fcc-info-toggle <?php echo esc_attr($fff_expanded_class); ?>" data-toggle-target="about-content">
        <span style="color: #FAF9F6 !important;">About Fluid Font Forge</span>
        <span class="fcc-toggle-icon" style="color: #FAF9F6 !important;">▼</span>
    </button>
    <div class="fcc-info-content <?php echo esc_attr($fff_expanded_class); ?>" id="about-content">
        <div style="color: var(--clr-txt); font-size: 16px; line-height: 1.6;">
            <p style="margin: 0 0 16px 0; color: var(--clr-txt);">
                I've been a font nerd for years. Great design presents content in a striking way without drawing attention to itself—much like how a skilled cinematographer draws you into a film's emotional depth without you consciously noticing the craft. When CSS clamp() arrived, it revolutionized responsive typography. Suddenly, fonts could scale fluidly across devices, keeping the focus on the message rather than awkward size jumps. I recently watched a YouTube presentation by a favorite WordPress expert who demonstrated a sophisticated font clamping calculator—better than most available tools. But it still wasn't quite enough for my needs. So I built this plugin. Enjoy.
            </p>
            <div style="background: rgba(60, 32, 23, 0.1); padding: 12px 16px; border-radius: 6px; border-left: 4px solid var(--clr-accent); margin-top: 20px; max-width: 78%; margin-left: auto; margin-right: auto;">
                <p style="margin: 0; font-size: 16px; opacity: 0.95; line-height: 1.5; color: var(--clr-txt);">
                    <b>Fluid Font Forge</b> by Jim R. (<a href="https://jimrforge.com" target="_blank" style="color: #CD5C5C; text-decoration: underline; font-weight: 600;">JimRForge</a>), developed with tremendous help from Claude AI (<a href="https://anthropic.com" target="_blank" style="color: #CD5C5C; text-decoration: underline; font-weight: 600;">Anthropic</a>), based on an original snippet by Imran Siddiq (<a href="https://websquadron.co.uk" target="_blank" style="color: #CD5C5C; text-decoration: underline; font-weight: 600;">WebSquadron</a>), in his Font Clamp Calculator (2.2).
                </p>
            </div>
        </div>
    </div>
</div>