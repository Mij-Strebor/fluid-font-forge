<?php

/**
 * Main Admin Interface Template
 *
 * @package FluidFontForge
 * @since 4.2.0
 * @version 5.1.0 - Updated UI with Forge header
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Extract template data
extract($template_data);
?>

<div class="wrap" style="background: var(--clr-pageBackground); padding: 20px; min-height: 100vh;">
    <?php echo "<!-- FILE IS LOADING: " . __FILE__ . " -->"; ?>
    <!-- Forge Header with Title -->
    <div class="fcc-header-section">
        <h1>Fluid Font Forge</h1>
    </div>

    <!-- Version Info (between header and About) -->
    <p style="font-family: 'Inter', sans-serif; font-weight: 400; font-size: 14px; font-style: italic; color: rgb(60, 67, 74); text-align: center; margin: -15vh auto 20px; max-width: 1280px; position: relative; z-index: 2;">
        Version <?php echo esc_html($version); ?>
    </p>

    <!-- About Panel (positioned by forge-header.css with negative margin) -->
    <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/about-panel.php'; ?>

    <!-- Loading State -->
    <?php include FLUID_FONT_FORGE_PATH . 'templates/admin/loading-screen.php'; ?>

        <!-- Main Section -->
        <div class="font-clamp-container" id="fcc-main-container">
            <div style="padding: 0 20px;">

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