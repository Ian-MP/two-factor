# Two-Factor Frontend

A WordPress plugin that adds front-end shortcode support for displaying and editing two-factor authentication settings.

## Description

This plugin extends two-factor authentication functionality by providing a front-end interface that users can access without going to the WordPress admin area. It's perfect for membership sites, customer portals, or any site where users need to manage their 2FA settings from the front-end.

## Features

- **Front-End Shortcode**: Easy-to-use `[two_factor_settings]` shortcode
- **Multiple Authentication Methods**:
  - Authenticator App (Google Authenticator, Authy, etc.)
  - Email-based codes
  - SMS-based codes
- **Interactive Interface**: Modern, user-friendly interface with real-time updates
- **Backup Codes**: Generate and display backup codes for account recovery
- **Secure**: AJAX-based updates with nonce verification
- **Responsive Design**: Works seamlessly on desktop, tablet, and mobile devices
- **Customizable**: Clean CSS and JavaScript that can be easily customized

## Installation

1. Download the plugin files or clone this repository
2. Upload the `two-factor-frontend` folder to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Add the shortcode `[two_factor_settings]` to any page or post

### Manual Installation

```bash
cd /path/to/wordpress/wp-content/plugins/
git clone https://github.com/Ian-MP/two-factor.git two-factor-frontend
```

Then activate the plugin in WordPress admin.

## Usage

### Basic Shortcode

Simply add the shortcode to any page or post:

```
[two_factor_settings]
```

### Example Page Setup

1. Create a new page in WordPress (e.g., "Security Settings")
2. Add the shortcode `[two_factor_settings]` to the page content
3. Publish the page
4. Users can now access their 2FA settings by visiting that page

### User Requirements

- Users must be logged in to view and edit their 2FA settings
- If a logged-out user visits a page with the shortcode, they'll see a message prompting them to log in

## Features in Detail

### Enable/Disable Two-Factor Authentication

Users can easily toggle two-factor authentication on or off with a visual toggle switch.

### Choose Authentication Method

Three authentication methods are available:

1. **Authenticator App**: Users can scan a QR code or manually enter a secret key
2. **Email**: Codes are sent to the user's registered email address
3. **SMS**: Codes are sent via text message to the user's phone

### Backup Codes

- 10 backup codes are automatically generated when 2FA is enabled
- Users can regenerate codes at any time
- Codes should be stored securely and can be used if primary 2FA method is unavailable

### Real-Time Updates

- AJAX-based form submission for instant feedback
- No page reloads required
- Visual indicators show current status

## File Structure

```
two-factor-frontend/
├── two-factor-frontend.php       # Main plugin file
├── templates/
│   └── settings-form.php         # Front-end settings form template
├── assets/
│   ├── css/
│   │   └── frontend.css          # Frontend styles
│   ├── js/
│   │   └── frontend.js           # Frontend JavaScript
│   └── images/
│       └── qr-placeholder.png    # QR code placeholder image
├── screenshots/
│   ├── screenshot-1.png          # 2FA settings display
│   ├── screenshot-2.png          # Authentication method selection
│   └── screenshot-3.png          # Example page with shortcode
├── README.md                      # This file
└── LICENSE                        # GPL v2 license

```

## Screenshots

### 1. Two-Factor Authentication Settings Page
![2FA Settings Display](screenshots/screenshot-1.png)

The main settings interface showing the toggle to enable/disable 2FA and current status.

### 2. Authentication Method Selection
![Authentication Methods](screenshots/screenshot-2.png)

Users can choose between Authenticator App, Email, or SMS methods.

### 3. Example Page with Shortcode
![Example Implementation](screenshots/screenshot-3.png)

A sample page showing how the shortcode appears in a typical WordPress theme.

## Customization

### Styling

The plugin uses clean, semantic CSS that can be easily customized. Override styles in your theme:

```css
/* Override in your theme's CSS */
.two-factor-frontend-container {
    max-width: 900px;
    /* Your custom styles */
}
```

### JavaScript Hooks

You can extend functionality by listening to custom events:

```javascript
jQuery(document).on('two_factor_settings_updated', function(event, data) {
    // Your custom code
});
```

## Security

- All AJAX requests are protected with WordPress nonces
- User authentication is verified before allowing any changes
- Input is sanitized and validated
- Settings are stored securely in WordPress user meta

## Compatibility

- **WordPress Version**: 5.0 or higher
- **PHP Version**: 7.0 or higher
- **Tested up to**: WordPress 6.4
- **Requires**: User must be logged in to access settings

## Development

### Requirements for Development

- WordPress development environment
- PHP 7.0+
- Modern web browser for testing

### Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## Known Issues

- QR code generation requires additional implementation for production use
- SMS functionality requires third-party service integration
- Email functionality requires proper SMTP configuration

## Future Enhancements

- Integration with popular 2FA plugins (WordPress Two-Factor, etc.)
- WebAuthn/FIDO2 support
- Trusted devices management
- Login history and activity log
- Customizable email templates
- Multi-language support (i18n ready)

## License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## Author

**Ian-MP**

- GitHub: [@Ian-MP](https://github.com/Ian-MP)
- Plugin URI: https://github.com/Ian-MP/two-factor

## Credits

This plugin is designed to be compatible with the official WordPress Two-Factor plugin and provides front-end accessibility to 2FA settings.

## Support

For bugs, feature requests, or questions, please [open an issue](https://github.com/Ian-MP/two-factor/issues) on GitHub.

## Changelog

### Version 1.0.0
- Initial release
- Front-end shortcode support
- Multiple authentication methods
- Backup codes generation
- Responsive design
- AJAX-based updates
<H1>2FA Front-end Display</H1>
# 'two-factor' plugin additions.<br>
# Front-end display via shortcode [two_factor_user_settings]<br>

<H2>Files</H2>
see 'class-two-factor-shortcode.php' for shortcode.<br>
see 'two-factor-frontend.css' for form layout.<br>
(Layout is for my theme Kadence - you might want to modify.)<br>
see 'add-to_functions.php' for addition to child-theme's functions.php.<br>
(This allows you to exclude 'providers' you don't want to make available.)<br>

<H2> Screenshots </H2>
see '2FA Front-end 01.jpg' for initial front-end screen.<br>
see '2FA Front-end 03.jpg' for edit screen.<br>
see '2FA Front-end recovery-codes.jpg' fro 'Recovery Code' regeneration.<br>
see '2FA Front-end saved.jpg' for 'save . . .' message at the top of the form.<br>
see '2FA Front-end revalidate.jpg' for 'revalidate' message.<br>
('Save . . .' button is hidden until revalidate completed.)<br>
Presently, 'Revalidate' does not return user to same page: working on that)<br>
see '2FA login 01.jpg' as example of 2FA showing in customised wp-login.php front-end<br>
