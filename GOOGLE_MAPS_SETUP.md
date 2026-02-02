# Google Maps API Setup Guide

This guide will help you set up Google Maps API for GPS location features in the profile page.

## Features

- ✅ GPS location detection
- ✅ Reverse geocoding (coordinates to address)
- ✅ Auto-fill address form from current location
- ✅ Fallback to OpenStreetMap if Google Maps API key is not configured

## Setup Instructions

### 1. Get Google Maps API Key

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the following APIs:
   - **Geocoding API** (required for address lookup)
   - **Maps JavaScript API** (optional, for future map features)
   - **Places API** (optional, for address autocomplete)

4. Create credentials:
   - Go to "APIs & Services" → "Credentials"
   - Click "Create Credentials" → "API Key"
   - Copy your API key

### 2. Configure API Key

1. Open `config/google_maps.php`
2. Replace `YOUR_GOOGLE_MAPS_API_KEY_HERE` with your actual API key:
   ```php
   define('GOOGLE_MAPS_API_KEY', 'YOUR_ACTUAL_API_KEY_HERE');
   ```

### 3. Set API Restrictions (Recommended)

For security, restrict your API key:

1. In Google Cloud Console, go to "APIs & Services" → "Credentials"
2. Click on your API key
3. Under "API restrictions":
   - Select "Restrict key"
   - Choose "Geocoding API" and "Maps JavaScript API"
4. Under "Application restrictions":
   - Select "HTTP referrers (web sites)"
   - Add your domain (e.g., `http://localhost/Makola/*`, `https://yourdomain.com/*`)

### 4. Billing Setup

- Google Maps API has a free tier: $200 credit per month
- This covers approximately 40,000 geocoding requests per month
- After free tier, you pay per request
- Set up billing in Google Cloud Console to use the API

## How It Works

1. **User clicks "Use GPS Location"** button in address form
2. **Browser requests location permission** (first time only)
3. **Gets GPS coordinates** from device
4. **Calls Google Maps Geocoding API** to convert coordinates to address
5. **Auto-fills form fields** with address details
6. **Falls back to OpenStreetMap** if Google Maps API key is not configured or fails

## Fallback Behavior

If Google Maps API key is not set or fails:
- System automatically uses OpenStreetMap Nominatim (free, no API key needed)
- Works seamlessly without any configuration
- May have slightly less accurate results in some regions

## Testing

1. Open the profile page
2. Click "Add Address"
3. Click "Use GPS Location" button
4. Allow location access when prompted
5. Form should auto-fill with your current address

## Troubleshooting

### "Failed to get address from location"
- Check if Google Maps API key is correctly set in `config/google_maps.php`
- Verify Geocoding API is enabled in Google Cloud Console
- Check API key restrictions (should allow your domain)
- Check browser console for error messages

### "Geolocation is not supported"
- Use a modern browser (Chrome, Firefox, Safari, Edge)
- Ensure you're using HTTPS (required for geolocation on most browsers)
- For localhost, HTTP is allowed

### "Permission denied"
- User must allow location access in browser settings
- Check browser location permissions for your site

## Cost Considerations

- **Free Tier**: $200 credit/month (covers ~40,000 requests)
- **After Free Tier**: 
  - Geocoding: $5.00 per 1,000 requests
  - Maps JavaScript API: $7.00 per 1,000 requests
- **OpenStreetMap**: Completely free, no limits (but requires attribution)

## Security Notes

- Never commit your API key to public repositories
- Use environment variables for production
- Restrict API key to specific domains/IPs
- Monitor API usage in Google Cloud Console
