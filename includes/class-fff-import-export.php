<?php
/**
 * Import/Export Functionality
 *
 * Handles JSON export and import of plugin settings with comprehensive
 * validation, security checks, and user feedback.
 *
 * @package    FluidFontForge
 * @subpackage ImportExport
 * @since      5.3.0
 * @version    5.3.0
 * @author     Jim R Forge
 * @link       https://jimrforge.com
 * @license    GPL-2.0-or-later
 */

namespace JimRForge\FluidFontForge;

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * FFF Import/Export Handler
 *
 * Provides import and export functionality for plugin configuration.
 * Enables backup/restore workflows and configuration sharing.
 *
 * @since 5.3.0
 */
class FFF_ImportExport
{
    /**
     * Plugin instance reference
     *
     * @var FluidFontForge
     */
    private $plugin;

    /**
     * Constructor
     *
     * @param FluidFontForge $plugin Main plugin instance
     */
    public function __construct($plugin)
    {
        $this->plugin = $plugin;
        $this->init_hooks();
    }

    /**
     * Initialize WordPress hooks
     *
     * @since 5.3.0
     */
    private function init_hooks()
    {
        // Handle export action
        add_action('admin_init', [$this, 'handle_export']);

        // Handle import action
        add_action('admin_init', [$this, 'handle_import']);

        // Display admin notices
        add_action('admin_notices', [$this, 'display_import_notices']);
    }

    /**
     * Handle settings export
     *
     * Generates JSON file download of current settings.
     *
     * @since 5.3.0
     */
    public function handle_export()
    {
        // Check if export requested
        if (!isset($_POST['fff_export_settings'])) {
            return;
        }

        // Security checks
        if (!isset($_POST['fff_export_nonce']) ||
            !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['fff_export_nonce'])), 'fff_export_settings')) {
            wp_die(esc_html__('Security check failed', 'fluid-font-forge'));
        }

        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('Insufficient permissions', 'fluid-font-forge'));
        }

        // Get settings
        $settings = $this->plugin->get_current_settings_export();

        // Add export metadata
        $settings['export_info'] = [
            'exported_at' => current_time('mysql'),
            'exported_by' => wp_get_current_user()->user_login,
            'site_url' => get_site_url(),
            'plugin_version' => FLUID_FONT_FORGE_VERSION
        ];

        // Generate filename
        $filename = sprintf(
            'fluid-font-forge-settings-%s.json',
            gmdate('Y-m-d-His')
        );

        // Send headers
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Output JSON
        echo wp_json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Handle settings import
     *
     * Processes uploaded JSON file and updates plugin settings.
     *
     * @since 5.3.0
     */
    public function handle_import()
    {
        // Check if import requested
        if (!isset($_POST['fff_import_settings'])) {
            return;
        }

        // Security checks
        if (!isset($_POST['fff_import_nonce']) ||
            !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['fff_import_nonce'])), 'fff_import_settings')) {
            set_transient('fff_import_result', [
                'success' => false,
                'message' => __('Security check failed', 'fluid-font-forge')
            ], 30);
            return;
        }

        if (!current_user_can('manage_options')) {
            set_transient('fff_import_result', [
                'success' => false,
                'message' => __('Insufficient permissions', 'fluid-font-forge')
            ], 30);
            return;
        }

        // Validate upload
        if (!isset($_FILES['fff_import_file']) ||
            $_FILES['fff_import_file']['error'] !== UPLOAD_ERR_OK) {
            set_transient('fff_import_result', [
                'success' => false,
                'message' => __('File upload failed', 'fluid-font-forge')
            ], 30);
            return;
        }

        $file = $_FILES['fff_import_file'];

        // Validate extension
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($file_ext !== 'json') {
            set_transient('fff_import_result', [
                'success' => false,
                'message' => __('File must be JSON format', 'fluid-font-forge')
            ], 30);
            return;
        }

        // Read and parse file
        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        $json_content = file_get_contents($file['tmp_name']);
        if ($json_content === false) {
            set_transient('fff_import_result', [
                'success' => false,
                'message' => __('Could not read file', 'fluid-font-forge')
            ], 30);
            return;
        }

        $settings = json_decode($json_content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            set_transient('fff_import_result', [
                'success' => false,
                'message' => sprintf(
                    __('Invalid JSON: %s', 'fluid-font-forge'),
                    json_last_error_msg()
                )
            ], 30);
            return;
        }

        // Validate settings
        $validated = $this->plugin->validate_settings_import($settings);
        if (is_wp_error($validated)) {
            set_transient('fff_import_result', [
                'success' => false,
                'message' => $validated->get_error_message()
            ], 30);
            return;
        }

        // Apply settings
        $applied = $this->plugin->apply_imported_settings($validated);

        if ($applied) {
            $result = [
                'success' => true,
                'message' => __('Settings imported successfully', 'fluid-font-forge')
            ];

            if (!empty($validated['validation_notes'])) {
                $result['warnings'] = $validated['validation_notes'];
            }

            set_transient('fff_import_result', $result, 30);
        } else {
            set_transient('fff_import_result', [
                'success' => false,
                'message' => __('Failed to apply settings', 'fluid-font-forge')
            ], 30);
        }

        // Redirect to prevent form resubmission
        wp_safe_redirect(add_query_arg('fff_import', '1', wp_get_referer()));
        exit;
    }

    /**
     * Display import result notices
     *
     * @since 5.3.0
     */
    public function display_import_notices()
    {
        // Only show on plugin page
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (!isset($_GET['page']) || sanitize_text_field(wp_unslash($_GET['page'])) !== 'fluid-font-forge') {
            return;
        }

        $result = get_transient('fff_import_result');
        if (!$result) {
            return;
        }

        delete_transient('fff_import_result');

        $class = $result['success'] ? 'notice-success' : 'notice-error';
        ?>
        <div class="notice <?php echo esc_attr($class); ?> is-dismissible">
            <p><strong><?php echo esc_html($result['message']); ?></strong></p>
            <?php if (!empty($result['warnings'])): ?>
                <ul style="margin-left: 20px;">
                    <?php foreach ($result['warnings'] as $warning): ?>
                        <li><?php echo esc_html($warning); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render export button
     *
     * @since 5.3.0
     */
    public function render_export_button()
    {
        ?>
        <form method="post" style="display: inline-block; margin-right: 8px;">
            <?php wp_nonce_field('fff_export_settings', 'fff_export_nonce'); ?>
            <button type="submit" name="fff_export_settings"
                    class="fff-btn fff-btn-secondary"
                    title="<?php esc_attr_e('Export current settings to JSON file', 'fluid-font-forge'); ?>">
                <span class="dashicons dashicons-download" style="margin-top: 3px;"></span>
                <?php esc_html_e('Export Settings', 'fluid-font-forge'); ?>
            </button>
        </form>
        <?php
    }

    /**
     * Render import form
     *
     * @since 5.3.0
     */
    public function render_import_form()
    {
        ?>
        <form method="post" enctype="multipart/form-data"
              id="fff-import-form" style="display: inline-block;">
            <?php wp_nonce_field('fff_import_settings', 'fff_import_nonce'); ?>
            <input type="file" name="fff_import_file"
                   accept=".json" id="fff-import-file" style="display: none;">
            <button type="button" class="fff-btn fff-btn-secondary"
                    id="fff-import-trigger"
                    title="<?php esc_attr_e('Import settings from JSON file', 'fluid-font-forge'); ?>">
                <span class="dashicons dashicons-upload" style="margin-top: 3px;"></span>
                <?php esc_html_e('Import Settings', 'fluid-font-forge'); ?>
            </button>
        </form>
        <script>
        (function() {
            var trigger = document.getElementById('fff-import-trigger');
            var fileInput = document.getElementById('fff-import-file');
            var form = document.getElementById('fff-import-form');

            if (trigger && fileInput && form) {
                trigger.addEventListener('click', function() {
                    fileInput.click();
                });

                fileInput.addEventListener('change', function(e) {
                    if (e.target.files.length > 0) {
                        var fileName = e.target.files[0].name;
                        var message = '<?php esc_html_e('Import settings from', 'fluid-font-forge'); ?> "' +
                                    fileName + '"?\n\n' +
                                    '<?php esc_html_e('Current settings will be replaced. This cannot be undone.', 'fluid-font-forge'); ?>';

                        if (confirm(message)) {
                            // Show loading state
                            trigger.disabled = true;
                            trigger.innerHTML = '<span class="dashicons dashicons-update-alt" style="margin-top: 3px; animation: spin 1s linear infinite;"></span> <?php esc_html_e('Importing...', 'fluid-font-forge'); ?>';
                            form.submit();
                        } else {
                            // Reset file input
                            fileInput.value = '';
                        }
                    }
                });
            }
        })();
        </script>
        <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        </style>
        <?php
    }
}
