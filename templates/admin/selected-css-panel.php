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

<div class="fcc-panel" style="margin-top: 8px;" id="selected-css-container">
    <div class="fcc-css-header">
        <h2 style="flex-grow: 1;" id="selected-code-title">Selected Class CSS</h2>
        <div id="selected-copy-button">
        </div>
    </div>
    <div style="background: white; border-radius: 6px; padding: 8px; border: 1px solid #d1d5db;">
        <pre id="class-code" style="font-size: 12px; white-space: pre-wrap; color: #111827; margin: 0;">/* Loading CSS output... */</pre>
    </div>
</div>