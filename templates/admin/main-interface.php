<?php

/**
 * Main Admin Interface Template
 * 
 * @package FluidFontForge
 * @since 4.2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Extract template data
extract($template_data);
?>

<div class="wrap" style="background: var(--clr-page-bg); padding: 20px; min-height: 100vh;">
    <div class="fcc-header-section">
        <h1 class="text-2xl font-bold mb-4">Fluid Font Forge (<?php echo esc_html($version); ?>)</h1><br>

        <!-- About Section -->
        <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/about-section.php'; ?>

        <!-- Loading State -->
        <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/loading-screen.php'; ?>

        <!-- Main Section -->
        <div class="font-clamp-container" id="fcc-main-container">
            <div style="padding: 20px;">

                <!-- How to Use Panel -->
                <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/how-to-use-panel.php'; ?>

                <!-- Enhanced Header Section -->
                <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/header-controls.php'; ?>

                <!-- Settings and Data Table - Side by Side -->
                <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/main-content-grid.php'; ?>

                <!-- Sample Panel with Viewport Slider -->
                <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/sample-panel.php'; ?>

                <!-- Full-Width Preview Section -->
                <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/preview-section.php'; ?>

                <!-- Enhanced CSS Output Containers -->
                <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/css-output-section.php'; ?>
            </div>
        </div>
    </div>
</div>