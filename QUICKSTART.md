# Quick Start Guide

This guide will help you get the Two-Factor Frontend plugin up and running in minutes.

## Installation Steps

1. **Download or Clone the Repository**
   ```bash
   cd /path/to/wordpress/wp-content/plugins/
   git clone https://github.com/Ian-MP/two-factor.git two-factor-frontend
   ```

2. **Activate the Plugin**
   - Go to WordPress Admin → Plugins
   - Find "Two-Factor Frontend"
   - Click "Activate"

3. **Create a Security Settings Page**
   - Go to Pages → Add New
   - Title: "Security Settings" (or your preferred name)
   - In the content editor, add the shortcode:
     ```
     [two_factor_settings]
     ```
   - Publish the page

4. **Test the Integration**
   - Log in to your WordPress site (as a regular user)
   - Navigate to the page you just created
   - You should see the Two-Factor Authentication settings interface

## Shortcode Reference

### Basic Usage

```
[two_factor_settings]
```

This is the only shortcode you need! It will display the complete two-factor authentication management interface.

## User Experience

When users visit a page with this shortcode:

1. **Logged-in Users** see:
   - Toggle to enable/disable 2FA
   - Choice of authentication methods (App, Email, SMS)
   - Setup instructions for their chosen method
   - Backup codes for account recovery
   - Current status display

2. **Logged-out Users** see:
   - A message prompting them to log in

## Adding to Your Theme

### In a Template File

If you want to add this directly to a theme template:

```php
<?php echo do_shortcode('[two_factor_settings]'); ?>
```

### In a Widget (if your theme supports shortcodes in widgets)

Just paste the shortcode into a text widget:
```
[two_factor_settings]
```

### Using Gutenberg Block

1. Add a "Shortcode" block
2. Enter: `[two_factor_settings]`

### Using Classic Editor

1. In the visual editor, just type: `[two_factor_settings]`
2. Or in the text editor, add: `[two_factor_settings]`

## Customizing the Appearance

The plugin includes well-structured CSS that you can override in your theme:

```css
/* Add to your theme's style.css */

/* Change the container width */
.two-factor-frontend-container {
    max-width: 900px;
}

/* Customize the primary button color */
.two-factor-frontend-container .button-primary {
    background: #ff5722;
}

.two-factor-frontend-container .button-primary:hover {
    background: #e64a19;
}

/* Customize toggle switch color */
.two-factor-toggle input:checked + .two-factor-toggle-slider {
    background-color: #4caf50;
}
```

## Menu Integration Example

Add a link to your security settings page in a menu:

1. Go to Appearance → Menus
2. Add a Custom Link
3. URL: `/security-settings/` (or your page slug)
4. Link Text: "Security Settings"
5. Save Menu

## Recommended Page Setup

For the best user experience, create a dedicated "Account" or "User Dashboard" section with:

- Profile Settings
- **Security Settings** (with the shortcode)
- Privacy Settings
- Notification Preferences

## Testing Checklist

- [ ] Shortcode displays correctly on the page
- [ ] Toggle switch enables/disables 2FA
- [ ] All three authentication methods can be selected
- [ ] Backup codes are displayed
- [ ] Save button updates settings
- [ ] Success message appears after saving
- [ ] Interface is responsive on mobile devices
- [ ] Non-logged-in users see the login prompt

## Troubleshooting

### Shortcode Displays as Text

**Problem:** You see `[two_factor_settings]` on the page instead of the interface.

**Solution:** 
- Make sure the plugin is activated
- Clear your cache (if using a caching plugin)
- Check that you're using the correct shortcode name

### Styling Issues

**Problem:** The interface looks broken or unstyled.

**Solution:**
- Clear browser cache
- Check browser console for CSS loading errors
- Ensure no theme conflicts with CSS classes

### JavaScript Not Working

**Problem:** Buttons don't respond, toggle doesn't work.

**Solution:**
- Check browser console for JavaScript errors
- Ensure jQuery is loaded
- Check for JavaScript conflicts with other plugins

## Next Steps

- Add the page to your navigation menu
- Test with different user roles
- Customize the styling to match your theme
- Add links to documentation for your users
- Consider translating strings for multilingual sites

## Support

For issues, questions, or feature requests:
- GitHub Issues: https://github.com/Ian-MP/two-factor/issues
- Review the full README.md for detailed documentation

## Security Note

This plugin stores 2FA settings in WordPress user meta. For production use, consider:
- Implementing actual TOTP (Time-based One-Time Password) generation
- Integrating with a real SMS provider for SMS codes
- Adding proper email functionality for email codes
- Testing thoroughly before deploying to production
