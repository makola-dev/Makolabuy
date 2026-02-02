# Cookie Consent System Guide

## Overview

A comprehensive, GDPR-compliant cookie consent system has been implemented for the Makola website. The system allows users to manage their cookie preferences and ensures compliance with privacy regulations.

## Features

### 1. Cookie Consent Banner
- **Appearance**: Fixed bottom banner that appears on first visit
- **Actions**: Accept All, Decline, or Customize Settings
- **Mobile Responsive**: Fully optimized for mobile devices
- **Accessibility**: ARIA labels and keyboard navigation support

### 2. Cookie Settings Modal
- **Granular Control**: Users can enable/disable specific cookie types
- **Categories**:
  - **Essential Cookies**: Required for website functionality (cannot be disabled)
  - **Analytics Cookies**: For website analytics and statistics
  - **Functional Cookies**: For enhanced functionality and personalization
  - **Marketing Cookies**: For advertising and marketing purposes

### 3. Cookie Management
- **Persistent Storage**: Preferences saved for 365 days
- **Automatic Loading**: Settings are automatically loaded on page visits
- **Event System**: Custom events for integration with other scripts

## Usage

### Basic Usage

The cookie consent banner will automatically appear on first visit. No additional code is required.

### Checking Cookie Consent

```javascript
// Check if user has given consent
if (window.CookieConsent && window.CookieConsent.hasConsent()) {
    // User has given consent
}

// Check if a specific cookie type is allowed
if (window.CookieConsent && window.CookieConsent.isAllowed('analytics')) {
    // Analytics cookies are allowed
    // Initialize analytics here
}
```

### Getting Cookie Settings

```javascript
// Get all cookie settings
const settings = window.CookieConsent.getSettings();
console.log(settings);
// Output: { essential: true, analytics: false, functional: true, marketing: false }
```

### Programmatically Opening Settings

```javascript
// Open cookie settings modal
if (window.CookieConsent) {
    window.CookieConsent.openSettings();
}
```

### Event Listeners

Listen for cookie consent events:

```javascript
// When user accepts all cookies
document.addEventListener('cookieConsentAccepted', function(e) {
    console.log('Cookie settings:', e.detail);
    // Initialize all cookie-dependent features
});

// When user declines non-essential cookies
document.addEventListener('cookieConsentDeclined', function(e) {
    console.log('Cookie settings:', e.detail);
    // Only initialize essential features
});

// When user saves custom settings
document.addEventListener('cookieSettingsSaved', function(e) {
    console.log('Cookie settings:', e.detail);
    // Initialize features based on settings
});

// When settings are loaded (on page load)
document.addEventListener('cookieSettingsLoaded', function(e) {
    console.log('Cookie settings:', e.detail);
    // Initialize features based on saved settings
});
```

### Example: Conditional Analytics

```javascript
// Only initialize Google Analytics if analytics cookies are allowed
document.addEventListener('cookieSettingsLoaded', function(e) {
    const settings = e.detail;
    
    if (settings.analytics) {
        // Initialize Google Analytics
        // gtag('config', 'GA_MEASUREMENT_ID');
    }
});

// Or check on page load
if (window.CookieConsent && window.CookieConsent.isAllowed('analytics')) {
    // Initialize analytics
}
```

### Example: Conditional Marketing Scripts

```javascript
// Only load marketing scripts if marketing cookies are allowed
document.addEventListener('cookieSettingsLoaded', function(e) {
    const settings = e.detail;
    
    if (settings.marketing) {
        // Load Facebook Pixel, Google Ads, etc.
        // fbq('init', 'FACEBOOK_PIXEL_ID');
    }
});
```

## Cookie Types

### Essential Cookies
- **Required**: Yes (cannot be disabled)
- **Purpose**: Core website functionality
- **Examples**: Session management, security, authentication

### Analytics Cookies
- **Required**: No
- **Purpose**: Website analytics and statistics
- **Examples**: Google Analytics, visitor tracking

### Functional Cookies
- **Required**: No
- **Purpose**: Enhanced functionality and personalization
- **Examples**: Language preferences, cart contents, user preferences

### Marketing Cookies
- **Required**: No
- **Purpose**: Advertising and marketing
- **Examples**: Facebook Pixel, Google Ads, retargeting pixels

## Customization

### Styling

The cookie consent banner can be customized by editing:
- `assets/css/cookie-consent.css`

### Behavior

The cookie consent behavior can be customized by editing:
- `assets/js/cookie-consent.js`

### Cookie Expiry

Default cookie expiry is 365 days. To change:

```javascript
// In cookie-consent.js
const COOKIE_EXPIRY_DAYS = 365; // Change this value
```

### Adding New Cookie Types

To add a new cookie type:

1. Edit `assets/js/cookie-consent.js`
2. Add to `COOKIE_TYPES` object:

```javascript
const COOKIE_TYPES = {
    // ... existing types
    custom: {
        name: 'Custom Cookies',
        description: 'Description of custom cookies',
        required: false,
        icon: 'bi-star'
    }
};
```

## Files

- `assets/css/cookie-consent.css` - Styling for cookie consent banner and modal
- `assets/js/cookie-consent.js` - Cookie consent logic and management
- `includes/footer.php` - HTML markup for banner and modal
- `cookie-notice.php` - Information page about cookies

## Browser Compatibility

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Mobile browsers (iOS Safari, Chrome Mobile)
- IE11+ (with polyfills if needed)

## Privacy Compliance

The cookie consent system is designed to be GDPR-compliant:
- Clear information about cookie usage
- Granular control over cookie types
- Easy opt-out mechanism
- Persistent preferences
- Link to detailed cookie notice

## Testing

1. **First Visit**: Banner should appear after 1 second
2. **Accept All**: All cookie types should be enabled
3. **Decline**: Only essential cookies should be enabled
4. **Custom Settings**: User should be able to toggle individual cookie types
5. **Persistence**: Settings should persist across page reloads
6. **Mobile**: Test on mobile devices for responsive design

## Troubleshooting

### Banner Not Appearing
- Check browser console for JavaScript errors
- Verify `cookie-consent.js` is loaded
- Check if consent cookie already exists

### Settings Not Saving
- Check browser console for errors
- Verify cookies are enabled in browser
- Check cookie domain and path settings

### Modal Not Opening
- Ensure Bootstrap 5 is loaded
- Check for JavaScript errors
- Verify modal HTML is in footer

## Support

For issues or questions:
1. Check browser console for errors
2. Verify all files are loaded correctly
3. Test in incognito/private mode
4. Clear browser cookies and test again
