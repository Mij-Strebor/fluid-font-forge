<?php

/**
 * Selected CSS Panel Template
 * 
 * @package FluidFontForge
 * @since 4.2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="fff-info-toggle-section" style="margin-top: 20px;">
    <div class="fff-info-header-static">
        <span id="selected-code-title" style="color: var(--clr-textLight);">Selected Class CSS</span>
        <div id="selected-copy-button" style="margin-left: auto;">
        </div>
    </div>
    <div class="fff-info-content-static">
        <div style="background: var(--clr-white); border-radius: var(--rad-standard); padding: var(--spc-sm); border: 1px solid var(--clr-code-border);">
            <pre id="class-code" style="font-size: var(--fnt-extraSmall); white-space: pre-wrap; color: var(--clr-code-text); margin: 0;">/* Loading CSS output... */</pre>
        </div>
    </div>
</div>