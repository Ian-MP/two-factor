<?php
/**
 * Plugin Name: Two-Factor Frontend
 * Plugin URI: https://github.com/Ian-MP/two-factor
 * Description: Adds front-end shortcode support for displaying and editing two-factor authentication settings
 * Version: 1.0.0
 * Author: Ian-MP
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: two-factor-frontend
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('TWO_FACTOR_FRONTEND_VERSION', '1.0.0');
define('TWO_FACTOR_FRONTEND_PATH', plugin_dir_path(__FILE__));
define('TWO_FACTOR_FRONTEND_URL', plugin_dir_url(__FILE__));

/**
 * Main plugin class
 */
class Two_Factor_Frontend {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'register_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_update_two_factor_settings', array($this, 'handle_ajax_update'));
    }
    
    /**
     * Register the shortcode
     */
    public function register_shortcode() {
        add_shortcode('two_factor_settings', array($this, 'shortcode_callback'));
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        global $post;
        
        // Only enqueue if shortcode is present
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'two_factor_settings')) {
            wp_enqueue_style(
                'two-factor-frontend',
                TWO_FACTOR_FRONTEND_URL . 'assets/css/frontend.css',
                array(),
                TWO_FACTOR_FRONTEND_VERSION
            );
            
            wp_enqueue_script(
                'two-factor-frontend',
                TWO_FACTOR_FRONTEND_URL . 'assets/js/frontend.js',
                array('jquery'),
                TWO_FACTOR_FRONTEND_VERSION,
                true
            );
            
            wp_localize_script('two-factor-frontend', 'twoFactorFrontend', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('two_factor_settings_nonce'),
                'strings' => array(
                    'success' => __('Settings updated successfully.', 'two-factor-frontend'),
                    'error' => __('An error occurred. Please try again.', 'two-factor-frontend'),
                )
            ));
        }
    }
    
    /**
     * Shortcode callback function
     */
    public function shortcode_callback($atts) {
        // Check if user is logged in
        if (!is_user_logged_in()) {
            return '<p class="two-factor-error">' . __('You must be logged in to manage two-factor authentication settings.', 'two-factor-frontend') . '</p>';
        }
        
        $user_id = get_current_user_id();
        $current_settings = $this->get_user_settings($user_id);
        
        ob_start();
        include TWO_FACTOR_FRONTEND_PATH . 'templates/settings-form.php';
        return ob_get_clean();
    }
    
    /**
     * Get user's two-factor settings
     */
    private function get_user_settings($user_id) {
        return array(
            'enabled' => get_user_meta($user_id, 'two_factor_enabled', true),
            'method' => get_user_meta($user_id, 'two_factor_method', true),
            'backup_codes' => get_user_meta($user_id, 'two_factor_backup_codes', true),
            'app_configured' => get_user_meta($user_id, 'two_factor_app_configured', true),
        );
    }
    
    /**
     * Handle AJAX update request
     */
    public function handle_ajax_update() {
        check_ajax_referer('two_factor_settings_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => __('Unauthorized', 'two-factor-frontend')));
        }
        
        $user_id = get_current_user_id();
        
        // Sanitize and update settings
        if (isset($_POST['two_factor_enabled'])) {
            update_user_meta($user_id, 'two_factor_enabled', sanitize_text_field($_POST['two_factor_enabled']));
        }
        
        if (isset($_POST['two_factor_method'])) {
            $method = sanitize_text_field($_POST['two_factor_method']);
            if (in_array($method, array('app', 'email', 'sms'))) {
                update_user_meta($user_id, 'two_factor_method', $method);
            }
        }
        
        wp_send_json_success(array(
            'message' => __('Settings updated successfully.', 'two-factor-frontend')
        ));
    }
    
    /**
     * Generate backup codes
     */
    public function generate_backup_codes() {
        $codes = array();
        for ($i = 0; $i < 10; $i++) {
            $codes[] = sprintf('%04d-%04d', rand(1000, 9999), rand(1000, 9999));
        }
        return $codes;
    }
}

// Initialize the plugin
new Two_Factor_Frontend();
