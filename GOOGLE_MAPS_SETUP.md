# Google Maps Setup Instructions

## Setting up Google Maps API for Event Manager

To enable the optional Google Maps functionality for event locations, you'll need to set up a Google Maps API key.

### Steps to get your API key:

1. **Go to Google Cloud Console**

   - Visit: https://console.cloud.google.com/

2. **Create or select a project**

   - Create a new project or select an existing one

3. **Enable the required APIs**

   - Go to "APIs & Services" > "Library"
   - Enable these APIs:
     - Maps JavaScript API
     - Places API
     - Geocoding API

4. **Create API Key**

   - Go to "APIs & Services" > "Credentials"
   - Click "Create Credentials" > "API Key"
   - Copy your API key

5. **Configure API Key (Recommended)**
   - Restrict your API key to specific websites (your domain)
   - Restrict to only the APIs you need (Maps JavaScript API, Places API, Geocoding API)

### Installation in your application:

1. **Add API key to your environment**

   - Add to your `.env` file:

   ```
   GOOGLE_MAPS_API_KEY=your_api_key_here
   ```

2. **Update the Vue components**

   - Replace `YOUR_GOOGLE_MAPS_API_KEY` in the following files:
     - `resources/js/Pages/Admin/Events/Create.vue`
     - `resources/js/Pages/Admin/Events/Edit.vue`
     - `resources/js/Pages/Events/Show.vue`

   Change this line:

   ```javascript
   const apiKey = 'YOUR_GOOGLE_MAPS_API_KEY';
   ```

   To this:

   ```javascript
   const apiKey =
     document
       .querySelector('meta[name="google-maps-api-key"]')
       ?.getAttribute('content') || '';
   ```

3. **Add meta tag to your layout**
   - In `resources/views/app.blade.php`, add this to the `<head>` section:
   ```html
   <meta
     name="google-maps-api-key"
     content="{{ config('maps.google.api_key') }}"
   />
   ```

### Features enabled with Google Maps:

- **Event Creation**: Interactive map for selecting event locations
- **Event Editing**: Update locations using the map interface
- **Event Display**: Show event location on a map for users
- **Place Search**: Auto-complete location search
- **Geocoding**: Automatic address lookup from coordinates

### Without API Key:

The application will still work perfectly fine without the Google Maps API key. Users can:

- Enter locations manually as text
- View events without maps
- All other functionality remains unchanged

The map features are completely optional and the app gracefully degrades when the API key is not available.

### Cost Considerations:

- Google Maps API has a free tier with generous limits
- Most small to medium applications will stay within free limits
- Monitor usage in Google Cloud Console
- Set up billing alerts if needed

For more information, visit: https://developers.google.com/maps/documentation/javascript/get-api-key
