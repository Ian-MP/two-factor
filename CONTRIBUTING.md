# Contributing to Two-Factor Frontend

Thank you for your interest in contributing to the Two-Factor Frontend plugin! This document provides guidelines for contributing to the project.

## How to Contribute

### Reporting Bugs

If you find a bug, please open an issue on GitHub with:

1. A clear, descriptive title
2. Steps to reproduce the issue
3. Expected behavior
4. Actual behavior
5. Your environment:
   - WordPress version
   - PHP version
   - Browser (if applicable)
   - Theme in use

### Suggesting Enhancements

We welcome feature suggestions! Please:

1. Check if the feature has already been suggested
2. Provide a clear use case
3. Explain why this feature would be useful
4. If possible, provide examples or mockups

### Pull Requests

We actively welcome your pull requests:

1. Fork the repo and create your branch from `main`
2. Make your changes
3. Test your changes thoroughly
4. Update documentation if needed
5. Write clear commit messages
6. Submit a pull request

## Development Setup

### Requirements

- WordPress 5.0+
- PHP 7.0+
- Modern web browser for testing
- Git

### Local Development

```bash
# Clone the repository
git clone https://github.com/Ian-MP/two-factor.git

# Create a WordPress local development environment
# (using Local by Flywheel, MAMP, Docker, etc.)

# Symlink or copy the plugin to wp-content/plugins/
ln -s /path/to/two-factor /path/to/wordpress/wp-content/plugins/two-factor-frontend

# Activate the plugin in WordPress admin
```

## Code Style

### PHP

- Follow [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- Use meaningful variable and function names
- Add PHPDoc comments for functions and classes
- Sanitize input and escape output

Example:
```php
/**
 * Get user's two-factor settings
 *
 * @param int $user_id User ID
 * @return array User settings
 */
private function get_user_settings($user_id) {
    return array(
        'enabled' => get_user_meta($user_id, 'two_factor_enabled', true),
        'method' => get_user_meta($user_id, 'two_factor_method', true),
    );
}
```

### JavaScript

- Use modern JavaScript (ES5 for browser compatibility)
- Follow WordPress JavaScript Coding Standards
- Use jQuery where appropriate
- Add comments for complex logic

Example:
```javascript
/**
 * Show message to user
 *
 * @param {string} message Message text
 * @param {string} type Message type (success|error)
 */
function showMessage(message, type) {
    // Implementation
}
```

### CSS

- Follow WordPress CSS Coding Standards
- Use BEM naming convention where appropriate
- Keep specificity low
- Make styles responsive

Example:
```css
/* Component container */
.two-factor-frontend-container {
    max-width: 800px;
    margin: 0 auto;
}

/* Component section */
.two-factor-section {
    margin-bottom: 30px;
    padding: 20px;
}
```

## Project Structure

```
two-factor-frontend/
├── two-factor-frontend.php       # Main plugin file
├── templates/                     # Template files
│   └── settings-form.php
├── assets/
│   ├── css/
│   │   └── frontend.css
│   ├── js/
│   │   └── frontend.js
│   └── images/
├── screenshots/                   # Plugin screenshots
├── README.md                      # Main documentation
├── QUICKSTART.md                  # Quick start guide
└── CONTRIBUTING.md               # This file
```

## Testing

Before submitting a pull request, please test:

1. **Functionality**
   - Enable/disable 2FA
   - Switch between authentication methods
   - Generate backup codes
   - Save settings

2. **Compatibility**
   - Test with latest WordPress version
   - Test with popular themes
   - Test with common plugins

3. **Responsiveness**
   - Desktop view
   - Tablet view
   - Mobile view

4. **Browsers**
   - Chrome
   - Firefox
   - Safari
   - Edge

5. **User Scenarios**
   - Logged-in user
   - Logged-out user
   - User with 2FA already enabled
   - First-time user

## Security

- Never commit sensitive data (passwords, API keys, etc.)
- Validate and sanitize all input
- Escape all output
- Use WordPress nonces for form submissions
- Follow WordPress security best practices

## Documentation

When adding features, please update:

- README.md - Main documentation
- QUICKSTART.md - If it affects getting started
- Inline code comments
- PHPDoc blocks

## Commit Messages

Write clear commit messages:

```
Add email verification for 2FA setup

- Implement email sending functionality
- Add verification code validation
- Update user interface for email flow
- Add tests for email verification
```

## Questions?

If you have questions about contributing:

1. Check existing issues and pull requests
2. Open a new issue with the "question" label
3. Reach out to the maintainers

## Code of Conduct

### Our Pledge

We pledge to make participation in our project a harassment-free experience for everyone, regardless of age, body size, disability, ethnicity, gender identity, level of experience, nationality, personal appearance, race, religion, or sexual identity.

### Our Standards

**Examples of behavior that contributes to a positive environment:**

- Using welcoming and inclusive language
- Being respectful of differing viewpoints
- Gracefully accepting constructive criticism
- Focusing on what is best for the community

**Examples of unacceptable behavior:**

- Trolling, insulting/derogatory comments, personal or political attacks
- Public or private harassment
- Publishing others' private information without permission
- Other conduct which could reasonably be considered inappropriate

## License

By contributing, you agree that your contributions will be licensed under the GPL v2 or later license.

## Recognition

Contributors will be:

- Listed in the README.md
- Mentioned in release notes
- Credited in commit messages

Thank you for contributing to Two-Factor Frontend!
