# IPTV Integration WordPress Plugin

WordPress plugin for integrating IPTV pricing plans, credit packs, and custom products with your WordPress site.

## Features

- **Automatic Product Sync**: Sync all products from your Laravel application
- **Auto-Generated Pages**: Automatically creates WordPress pages with embedded checkout iframes
- **Single-User Support**: Simplified version for single-user installations
- **Full-Page Iframes**: Creates full-page checkout experiences
- **Easy Management**: Simple admin interface for configuration

## Installation

1. Upload the `wordpress-plugin` folder to your WordPress `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to **IPTV Integration** in the WordPress admin menu
4. Enter your API URL and generate an API token from your Laravel admin panel
5. Click "Test Connection" to verify the connection
6. Click "Sync Products" to create WordPress pages for all your products

## Configuration

### API URL
Enter your Laravel API base URL (e.g., `https://your-domain.com/api`)

### API Token
1. Log in to your Laravel admin panel
2. Navigate to the API token generation page (or use the API endpoint)
3. Generate a new token
4. Copy and paste it into the WordPress plugin settings

## Usage

1. **Sync Products**: Click "Sync Products" to fetch all products from your Laravel application and create WordPress pages
2. **View Pages**: All created pages will appear in the "Product Pages" list
3. **Edit Pages**: Click "Edit" to modify any generated page
4. **Clear Data**: Use "Clear All Data" to remove all created pages and reset the plugin

## API Endpoints

The plugin uses the following Laravel API endpoints:

- `GET /api/wordpress/products` - Fetch all products
- `POST /api/wordpress/tokens/generate` - Generate API token
- `GET /api/wordpress/tokens` - List all tokens
- `DELETE /api/wordpress/tokens/{tokenId}` - Revoke a token

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Laravel application with WordPress Integration API enabled
- Valid API token from Laravel admin panel

## Support

For issues or questions, please contact your system administrator.

