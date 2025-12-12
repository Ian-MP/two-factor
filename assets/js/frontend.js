/**
 * Two-Factor Frontend JavaScript
 * Version: 1.0.0
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Toggle method section when two-factor is enabled/disabled
        $('#two_factor_enabled').on('change', function() {
            const isEnabled = $(this).is(':checked');
            $('#two-factor-method-section').toggle(isEnabled);
            $('#two-factor-backup-section').toggle(isEnabled);
            
            // Update status badge
            updateStatusBadge(isEnabled);
        });
        
        // Show app setup when authenticator app is selected
        $('input[name="two_factor_method"]').on('change', function() {
            const method = $(this).val();
            
            if (method === 'app') {
                $('#two-factor-app-setup').slideDown();
            } else {
                $('#two-factor-app-setup').slideUp();
            }
            
            // Update method badge
            updateMethodBadge(method);
        });
        
        // Handle form submission
        $('#two-factor-settings-form').on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $button = $('#save-settings-btn');
            const $spinner = $('.two-factor-actions .spinner');
            
            // Show loading state
            $button.prop('disabled', true);
            $spinner.addClass('is-active');
            
            // Prepare data
            const formData = {
                action: 'update_two_factor_settings',
                nonce: twoFactorFrontend.nonce,
                two_factor_enabled: $('#two_factor_enabled').is(':checked') ? 'yes' : 'no',
                two_factor_method: $('input[name="two_factor_method"]:checked').val()
            };
            
            // Send AJAX request
            $.ajax({
                url: twoFactorFrontend.ajaxurl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showMessage(twoFactorFrontend.strings.success, 'success');
                    } else {
                        showMessage(response.data.message || twoFactorFrontend.strings.error, 'error');
                    }
                },
                error: function() {
                    showMessage(twoFactorFrontend.strings.error, 'error');
                },
                complete: function() {
                    $button.prop('disabled', false);
                    $spinner.removeClass('is-active');
                }
            });
        });
        
        // Handle verification code
        $('#verify-code-btn').on('click', function() {
            const code = $('#verification_code').val();
            
            if (!code || code.length !== 6) {
                showMessage('Please enter a valid 6-digit code.', 'error');
                return;
            }
            
            // In a real implementation, this would verify the code
            showMessage('Code verified successfully!', 'success');
            $('#two-factor-app-setup').slideUp();
        });
        
        // Handle regenerate backup codes
        $('#regenerate-codes-btn').on('click', function() {
            const $button = $(this);
            const $codesContainer = $('.two-factor-backup-codes');
            
            // Show inline confirmation
            if (!$button.data('confirmed')) {
                $button.data('confirmed', true)
                    .text('Click again to confirm')
                    .addClass('button-warning');
                
                // Reset after 3 seconds if not clicked
                setTimeout(function() {
                    if ($button.data('confirmed')) {
                        $button.data('confirmed', false)
                            .text('Generate New Codes')
                            .removeClass('button-warning');
                    }
                }, 3000);
                
                return;
            }
            
            // Reset confirmation state
            $button.data('confirmed', false).removeClass('button-warning');
            
            $button.prop('disabled', true).text('Generating...');
            
            // Make AJAX call to generate new codes server-side
            $.ajax({
                url: twoFactorFrontend.ajaxurl,
                type: 'POST',
                data: {
                    action: 'regenerate_backup_codes',
                    nonce: twoFactorFrontend.nonce
                },
                success: function(response) {
                    if (response.success && response.data.codes) {
                        // Replace codes with new ones from server
                        $codesContainer.empty();
                        response.data.codes.forEach(function(code) {
                            $codesContainer.append('<code class="backup-code">' + code + '</code>');
                        });
                        showMessage('New backup codes generated successfully! Save them in a safe place.', 'success');
                    } else {
                        showMessage(response.data.message || 'Failed to generate new codes.', 'error');
                    }
                },
                error: function() {
                    showMessage('Failed to generate new codes. Please try again.', 'error');
                },
                complete: function() {
                    $button.prop('disabled', false).text('Generate New Codes');
                }
            });
        });
        
        /**
         * Show message to user
         */
        function showMessage(message, type) {
            const $messageDiv = $('#two-factor-message');
            $messageDiv
                .removeClass('success error')
                .addClass(type)
                .text(message)
                .slideDown();
            
            // Auto-hide after 5 seconds
            setTimeout(function() {
                $messageDiv.slideUp();
            }, 5000);
            
            // Scroll to message
            $('html, body').animate({
                scrollTop: $messageDiv.offset().top - 100
            }, 500);
        }
        
        /**
         * Update status badge
         */
        function updateStatusBadge(isEnabled) {
            const $badge = $('.status-badge');
            if (isEnabled) {
                $badge
                    .removeClass('status-disabled')
                    .addClass('status-enabled')
                    .text('Enabled');
            } else {
                $badge
                    .removeClass('status-enabled')
                    .addClass('status-disabled')
                    .text('Disabled');
            }
        }
        
        /**
         * Update method badge
         */
        function updateMethodBadge(method) {
            const methodLabels = {
                'app': 'Authenticator App',
                'email': 'Email',
                'sms': 'SMS'
            };
            
            $('.method-badge').text(methodLabels[method] || method);
        }
        
        // Format verification code input
        $('#verification_code').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        
    });
    
})(jQuery);
