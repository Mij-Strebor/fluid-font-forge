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

<div class="fcc-info-toggle-section">
    <button class="fcc-info-toggle expanded" data-toggle-target="about-content">
        <span style="color: #FAF9F6 !important;">ℹ️ About Fluid Font Forge</span>
        <span class="fcc-toggle-icon" style="color: #FAF9F6 !important;">▼</span>
    </button>
    <div class="fcc-info-content expanded" id="about-content">
        <div style="color: var(--clr-txt); font-size: 14px; line-height: 1.6;">
            <p style="margin: 0 0 16px 0; color: var(--clr-txt);">
                I've been a font nerd for a while. I enjoy seeing designers presenting an attraction in a striking way that doesn't attract attention itself. It's exactly like a director, or cinematographer moves you into the emotional depth of a movie without you knowing it. When CSS clamp() came along, there was an explosion of responsive font management. That gave the font the ability to stop drawing attention to itself and present the message effectively. I recently visited a YouTube presentation by a favorite WordPress guru. There was a sophisticated calculator for font clamping demo development. This was better than many of the websites out there. But it wasn't enough! Here's my attempt. Enjoy.
            </p>
            <div style="background: rgba(60, 32, 23, 0.1); padding: 12px 16px; border-radius: 6px; border-left: 4px solid var(--clr-accent); margin-top: 20px;">
                <p style="margin: 0; font-size: 13px; opacity: 0.95; line-height: 1.5; color: var(--clr-txt);">
                    Fluid Font Forge by Jim R. (<a href="https://jimrweb.com" target="_blank" style="color: #CD5C5C; text-decoration: underline; font-weight: 600;">JimRWeb</a>), developed with tremendous help from Claude AI (<a href="https://anthropic.com" target="_blank" style="color: #CD5C5C; text-decoration: underline; font-weight: 600;">Anthropic</a>), based on an original snippet by Imran Siddiq (<a href="https://websquadron.co.uk" target="_blank" style="color: #CD5C5C; text-decoration: underline; font-weight: 600;">WebSquadron</a>), in his Font Clamp Calculator (2.2).
                </p>
            </div>
        </div>
    </div>
</div>