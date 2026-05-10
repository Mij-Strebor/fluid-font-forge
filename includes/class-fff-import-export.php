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

        // Handle import action (form POST fallback)
        add_action('admin_init', [$this, 'handle_import']);

        // Handle import via AJAX (used by JS fetch)
        add_action('wp_ajax_fff_import_settings', [$this, 'handle_import_ajax']);

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
        $settings['fff_format'] = 'fluid-font-forge';
        $settings['export_info'] = [
            'exported_at' => current_time('mysql'),
            'exported_by' => wp_get_current_user()->user_login,
            'site_url' => get_site_url(),
            'plugin_version' => FLUID_FONT_FORGE_VERSION
        ];

        // Build filename — include project/customer slug when set
        $project_customer = trim($settings['settings']['projectCustomer'] ?? '');
        $customer_slug = '';
        if ($project_customer !== '') {
            $customer_slug = strtolower(str_replace(' ', '-', $project_customer)) . '-';
        }
        $filename = sprintf(
            'fluid-font-forge-settings-%s%s.json',
            $customer_slug,
            current_time('Y-m-d-His')
        );

        // Signal to JS that the download response has arrived so it can reset the spinner
        setcookie('fff_export_done', '1', time() + 60, '/');

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
        if (!isset($_FILES['fff_import_file']['error']) ||
            (int) $_FILES['fff_import_file']['error'] !== UPLOAD_ERR_OK) {
            set_transient('fff_import_result', [
                'success' => false,
                'message' => __('File upload failed', 'fluid-font-forge')
            ], 30);
            return;
        }

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $file_tmp = $_FILES['fff_import_file']['tmp_name'] ?? '';

        if (!is_uploaded_file($file_tmp)) {
            set_transient('fff_import_result', [
                'success' => false,
                'message' => __('File upload failed', 'fluid-font-forge')
            ], 30);
            return;
        }

        $file_name = isset($_FILES['fff_import_file']['name'])
            ? sanitize_file_name(wp_unslash($_FILES['fff_import_file']['name']))
            : '';

        // Validate extension
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if ($file_ext !== 'json') {
            set_transient('fff_import_result', [
                'success' => false,
                'message' => __('File must be JSON format', 'fluid-font-forge')
            ], 30);
            return;
        }

        // Read and parse file
        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        $json_content = file_get_contents($file_tmp);
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
                    /* translators: %s: JSON error message */
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
     * Handle settings import via AJAX fetch (called by JS)
     *
     * @since 5.3.0
     */
    public function handle_import_ajax()
    {
        if (!isset($_POST['nonce']) ||
            !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'fluid_font_nonce')) {
            wp_send_json_error(['message' => __('Security check failed', 'fluid-font-forge')]);
            return;
        }

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Insufficient permissions', 'fluid-font-forge')]);
            return;
        }

        if (!isset($_FILES['fff_import_file']['error']) ||
            (int) $_FILES['fff_import_file']['error'] !== UPLOAD_ERR_OK) {
            wp_send_json_error(['message' => __('File upload failed', 'fluid-font-forge')]);
            return;
        }

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $file_tmp = $_FILES['fff_import_file']['tmp_name'] ?? '';

        if (!is_uploaded_file($file_tmp)) {
            wp_send_json_error(['message' => __('File upload failed', 'fluid-font-forge')]);
            return;
        }

        $file_name = isset($_FILES['fff_import_file']['name'])
            ? sanitize_file_name(wp_unslash($_FILES['fff_import_file']['name']))
            : '';

        if (strtolower(pathinfo($file_name, PATHINFO_EXTENSION)) !== 'json') {
            wp_send_json_error(['message' => __('File must be JSON format', 'fluid-font-forge')]);
            return;
        }

        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        $json_content = file_get_contents($file_tmp);
        if ($json_content === false) {
            wp_send_json_error(['message' => __('Could not read file', 'fluid-font-forge')]);
            return;
        }

        $settings = json_decode($json_content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            wp_send_json_error(['message' => sprintf(
                /* translators: %s: JSON error message */
                __('Invalid JSON: %s', 'fluid-font-forge'),
                json_last_error_msg()
            )]);
            return;
        }

        $validated = $this->plugin->validate_settings_import($settings);
        if (is_wp_error($validated)) {
            wp_send_json_error(['message' => $validated->get_error_message()]);
            return;
        }

        if (!$this->plugin->apply_imported_settings($validated)) {
            wp_send_json_error(['message' => __('Failed to apply settings', 'fluid-font-forge')]);
            return;
        }

        $response = ['message' => __('Settings imported successfully', 'fluid-font-forge')];
        if (!empty($validated['validation_notes'])) {
            $response['warnings'] = $validated['validation_notes'];
        }
        wp_send_json_success($response);
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
        <form id="fff-export-form" method="post" style="display: inline-block; margin-right: 8px;">
            <?php wp_nonce_field('fff_export_settings', 'fff_export_nonce'); ?>
            <input type="hidden" name="fff_export_settings" value="1">
            <button type="submit"
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
        <div style="display: inline-block;">
            <input type="file" name="fff_import_file"
                   accept=".json" id="fff-import-file"
                   style="position:fixed;left:-9999px;top:-9999px;width:0;height:0;opacity:0;"
                   tabindex="-1" aria-hidden="true">
            <button type="button" class="fff-btn fff-btn-secondary"
                    id="fff-import-trigger"
                    title="<?php esc_attr_e('Import settings from JSON file', 'fluid-font-forge'); ?>">
                <span class="dashicons dashicons-upload" style="margin-top: 3px;"></span>
                <?php esc_html_e('Import Settings', 'fluid-font-forge'); ?>
            </button>
        </div>
        <script>
        (function() {
            var trigger = document.getElementById('fff-import-trigger');
            var fileInput = document.getElementById('fff-import-file');

            if (!trigger || !fileInput) return;

            trigger.addEventListener('click', function() {
                fileInput.click();
            });

            fileInput.addEventListener('change', function(e) {
                if (!e.target.files.length) return;

                var file = e.target.files[0];
                var fileName = file.name;

                // Reset immediately so the same file can retrigger change if cancelled
                fileInput.value = '';

                if (!window.fluidFontNotices) {
                    window.fluidFontNotices = new WordPressAdminNotices();
                }

                var fileExt = fileName.split('.').pop().toLowerCase();
                if (fileExt !== 'json') {
                    window.fluidFontNotices.alert('<strong><?php esc_html_e('Invalid File Type', 'fluid-font-forge'); ?>:</strong> <?php esc_html_e('Please select a .json file exported from Fluid Font Forge.', 'fluid-font-forge'); ?>');
                    return;
                }

                var message = '<?php esc_html_e('Import settings from', 'fluid-font-forge'); ?> <strong>"' +
                            fileName + '"</strong><br><br>' +
                            '<?php esc_html_e('Current settings will be replaced. This cannot be undone.', 'fluid-font-forge'); ?>';

                window.fluidFontNotices.confirm(message, function() {
                    trigger.disabled = true;
                    trigger.innerHTML = '<span class="dashicons dashicons-update-alt" style="margin-top: 3px;"></span> <?php esc_html_e('Importing...', 'fluid-font-forge'); ?>';

                    // Double rAF ensures the browser paints the spinner state before the fetch starts
                    requestAnimationFrame(function() { requestAnimationFrame(function() {

                    var formData = new FormData();
                    formData.append('action', 'fff_import_settings');
                    formData.append('nonce', window.fluidfontforgeAjax.nonce);
                    formData.append('fff_import_file', file, fileName);

                    fetch(window.fluidfontforgeAjax.ajaxurl, {
                        method: 'POST',
                        body: formData,
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(result) {
                        if (result.success) {
                            if (window.fontClampAdvanced) {
                                window.fontClampAdvanced.suppressUnloadWarning = true;
                            }
                            window.location.reload();
                        } else {
                            throw new Error(result.data ? result.data.message : '<?php esc_html_e('Import failed', 'fluid-font-forge'); ?>');
                        }
                    })
                    .catch(function(error) {
                        trigger.disabled = false;
                        trigger.innerHTML = '<span class="dashicons dashicons-upload" style="margin-top: 3px;"></span> <?php esc_html_e('Import Settings', 'fluid-font-forge'); ?>';
                        if (!window.fluidFontNotices) {
                            window.fluidFontNotices = new WordPressAdminNotices();
                        }
                        window.fluidFontNotices.alert('<strong><?php esc_html_e('Import Failed', 'fluid-font-forge'); ?>:</strong> ' + error.message);
                    });

                    }); }); // end double rAF
                });
            });
        })();
        </script>
        <?php
    }
}
