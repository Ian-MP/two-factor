<?php
/**
 * Two-Factor Settings Form Template
 * 
 * @var int $user_id Current user ID
 * @var array $current_settings User's current settings
 */

if (!defined('ABSPATH')) {
    exit;
}

$enabled = !empty($current_settings['enabled']) ? $current_settings['enabled'] : 'no';
$method = !empty($current_settings['method']) ? $current_settings['method'] : 'app';
$app_configured = !empty($current_settings['app_configured']);
?>

<div class="two-factor-frontend-container">
    <div class="two-factor-header">
        <h2><?php _e('Two-Factor Authentication Settings', 'two-factor-frontend'); ?></h2>
        <p class="description"><?php _e('Enhance your account security by enabling two-factor authentication.', 'two-factor-frontend'); ?></p>
    </div>
    
    <div id="two-factor-message" class="two-factor-message" style="display: none;"></div>
    
    <form id="two-factor-settings-form" class="two-factor-form">
        <?php wp_nonce_field('two_factor_settings', 'two_factor_nonce'); ?>
        
        <!-- Enable/Disable Two-Factor -->
        <div class="two-factor-section">
            <h3><?php _e('Status', 'two-factor-frontend'); ?></h3>
            <div class="two-factor-field">
                <label class="two-factor-toggle">
                    <input type="checkbox" 
                           name="two_factor_enabled" 
                           id="two_factor_enabled" 
                           value="yes" 
                           <?php checked($enabled, 'yes'); ?>>
                    <span class="two-factor-toggle-slider"></span>
                </label>
                <label for="two_factor_enabled">
                    <?php _e('Enable Two-Factor Authentication', 'two-factor-frontend'); ?>
                </label>
            </div>
        </div>
        
        <!-- Two-Factor Method -->
        <div class="two-factor-section" id="two-factor-method-section" style="<?php echo $enabled === 'yes' ? '' : 'display: none;'; ?>">
            <h3><?php _e('Authentication Method', 'two-factor-frontend'); ?></h3>
            
            <div class="two-factor-methods">
                <label class="two-factor-method-option">
                    <input type="radio" 
                           name="two_factor_method" 
                           value="app" 
                           <?php checked($method, 'app'); ?>>
                    <div class="two-factor-method-card">
                        <div class="two-factor-method-icon">
                            <span class="dashicons dashicons-smartphone"></span>
                        </div>
                        <div class="two-factor-method-info">
                            <strong><?php _e('Authenticator App', 'two-factor-frontend'); ?></strong>
                            <p><?php _e('Use an authenticator app like Google Authenticator or Authy', 'two-factor-frontend'); ?></p>
                        </div>
                    </div>
                </label>
                
                <label class="two-factor-method-option">
                    <input type="radio" 
                           name="two_factor_method" 
                           value="email" 
                           <?php checked($method, 'email'); ?>>
                    <div class="two-factor-method-card">
                        <div class="two-factor-method-icon">
                            <span class="dashicons dashicons-email"></span>
                        </div>
                        <div class="two-factor-method-info">
                            <strong><?php _e('Email', 'two-factor-frontend'); ?></strong>
                            <p><?php _e('Receive codes via email', 'two-factor-frontend'); ?></p>
                        </div>
                    </div>
                </label>
                
                <label class="two-factor-method-option">
                    <input type="radio" 
                           name="two_factor_method" 
                           value="sms" 
                           <?php checked($method, 'sms'); ?>>
                    <div class="two-factor-method-card">
                        <div class="two-factor-method-icon">
                            <span class="dashicons dashicons-phone"></span>
                        </div>
                        <div class="two-factor-method-info">
                            <strong><?php _e('SMS', 'two-factor-frontend'); ?></strong>
                            <p><?php _e('Receive codes via text message', 'two-factor-frontend'); ?></p>
                        </div>
                    </div>
                </label>
            </div>
        </div>
        
        <!-- App Setup Instructions -->
        <div class="two-factor-section two-factor-app-setup" id="two-factor-app-setup" style="display: none;">
            <h3><?php _e('Setup Authenticator App', 'two-factor-frontend'); ?></h3>
            <div class="two-factor-setup-steps">
                <ol>
                    <li><?php _e('Download an authenticator app (Google Authenticator, Authy, etc.)', 'two-factor-frontend'); ?></li>
                    <li><?php _e('Scan the QR code below with your app', 'two-factor-frontend'); ?></li>
                    <li><?php _e('Enter the code from your app to verify', 'two-factor-frontend'); ?></li>
                </ol>
                
                <div class="two-factor-qr-code">
                    <img src="<?php echo TWO_FACTOR_FRONTEND_URL; ?>assets/images/qr-placeholder.png" 
                         alt="<?php _e('QR Code', 'two-factor-frontend'); ?>"
                         class="qr-code-image">
                    <p class="two-factor-secret-key">
                        <?php _e('Secret Key:', 'two-factor-frontend'); ?> 
                        <code>ABCD-EFGH-IJKL-MNOP</code>
                    </p>
                </div>
                
                <div class="two-factor-verification">
                    <label for="verification_code"><?php _e('Verification Code:', 'two-factor-frontend'); ?></label>
                    <input type="text" 
                           id="verification_code" 
                           name="verification_code" 
                           placeholder="000000"
                           maxlength="6"
                           pattern="[0-9]{6}">
                    <button type="button" class="button" id="verify-code-btn">
                        <?php _e('Verify', 'two-factor-frontend'); ?>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Backup Codes -->
        <div class="two-factor-section" id="two-factor-backup-section" style="<?php echo $enabled === 'yes' ? '' : 'display: none;'; ?>">
            <h3><?php _e('Backup Codes', 'two-factor-frontend'); ?></h3>
            <p class="description">
                <?php _e('Save these backup codes in a safe place. You can use them to access your account if you lose access to your two-factor authentication method.', 'two-factor-frontend'); ?>
            </p>
            <div class="two-factor-backup-codes">
                <?php
                // Use backup codes passed from the shortcode callback
                foreach ($backup_codes as $code) {
                    echo '<code class="backup-code">' . esc_html($code) . '</code>';
                }
                ?>
            </div>
            <button type="button" class="button" id="regenerate-codes-btn">
                <?php _e('Generate New Codes', 'two-factor-frontend'); ?>
            </button>
        </div>
        
        <!-- Current Status Display -->
        <div class="two-factor-section two-factor-status">
            <h3><?php _e('Current Status', 'two-factor-frontend'); ?></h3>
            <div class="two-factor-status-info">
                <p>
                    <strong><?php _e('Status:', 'two-factor-frontend'); ?></strong>
                    <span class="status-badge <?php echo $enabled === 'yes' ? 'status-enabled' : 'status-disabled'; ?>">
                        <?php echo $enabled === 'yes' ? __('Enabled', 'two-factor-frontend') : __('Disabled', 'two-factor-frontend'); ?>
                    </span>
                </p>
                <?php if ($enabled === 'yes') : ?>
                <p>
                    <strong><?php _e('Method:', 'two-factor-frontend'); ?></strong>
                    <span class="method-badge">
                        <?php
                        $method_labels = array(
                            'app' => __('Authenticator App', 'two-factor-frontend'),
                            'email' => __('Email', 'two-factor-frontend'),
                            'sms' => __('SMS', 'two-factor-frontend'),
                        );
                        echo isset($method_labels[$method]) ? $method_labels[$method] : $method;
                        ?>
                    </span>
                </p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="two-factor-actions">
            <button type="submit" class="button button-primary" id="save-settings-btn">
                <?php _e('Save Settings', 'two-factor-frontend'); ?>
            </button>
            <span class="spinner"></span>
        </div>
    </form>
</div>
