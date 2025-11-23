# WordPress API Integration Documentation

## Overview

This document describes the WordPress API integration for the IPTV management system. The API allows WordPress sites to fetch product information and create checkout pages automatically.

## Authentication

All API endpoints require authentication using Laravel Sanctum Bearer tokens.

**Header Format:**
```
Authorization: Bearer {your-api-token}
Accept: application/json
```

## API Endpoints

### 1. Get Products

**Endpoint:** `GET /api/wordpress/products`

**Description:** Returns all active products (pricing plans, credit packs, and custom products) for the authenticated admin user.

**Response:**
```json
{
    "success": true,
    "data": {
        "pricing_plans": [
            {
                "id": 1,
                "type": "pricing_plan",
                "name": "Basic Plan",
                "price": 9.99,
                "formatted_price": "$9.99",
                "checkout_url": "https://your-domain.com/checkout?plan_id=1&source=wordpress"
            }
        ],
        "credit_packs": [],
        "custom_products": []
    },
    "user": {
        "id": 1,
        "name": "Admin User",
        "email": "admin@example.com",
        "role": "admin"
    }
}
```

### 2. Generate API Token

**Endpoint:** `POST /api/wordpress/tokens/generate`

**Description:** Generates a new API token for WordPress integration.

**Response:**
```json
{
    "success": true,
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
    "message": "API token generated successfully. Store this securely in your WordPress plugin settings."
}
```

### 3. Get Tokens

**Endpoint:** `GET /api/wordpress/tokens`

**Description:** Lists all API tokens for the authenticated user.

**Response:**
```json
{
    "success": true,
    "tokens": [
        {
            "id": 1,
            "name": "wordpress-integration-abc123",
            "abilities": ["wordpress:read"],
            "last_used_at": "2025-01-15T10:30:00.000000Z",
            "created_at": "2025-01-10T08:00:00.000000Z"
        }
    ]
}
```

### 4. Revoke Token

**Endpoint:** `DELETE /api/wordpress/tokens/{tokenId}`

**Description:** Revokes (deletes) a specific API token.

**Response:**
```json
{
    "success": true,
    "message": "Token revoked successfully."
}
```

## Single-User Version

This is a **single-user version** of the WordPress integration. Unlike the multi-user version:

- **No admin_id filtering**: All active products are returned regardless of admin ownership
- **Simplified access**: Only requires admin authentication
- **No source-based filtering**: Products are not filtered by source (unless specified in token)

## Error Responses

All endpoints return standard error responses:

```json
{
    "success": false,
    "message": "Error message here"
}
```

**HTTP Status Codes:**
- `200` - Success
- `401` - Unauthorized (invalid or missing token)
- `403` - Forbidden (insufficient permissions)
- `404` - Not found
- `500` - Server error

## Usage Example

```javascript
// Fetch products
fetch('https://your-domain.com/api/wordpress/products', {
    headers: {
        'Authorization': 'Bearer YOUR_API_TOKEN',
        'Accept': 'application/json'
    }
})
.then(response => response.json())
.then(data => {
    console.log(data.data.pricing_plans);
});
```

## Security Notes

- Store API tokens securely
- Never expose tokens in client-side code
- Rotate tokens regularly
- Revoke unused tokens
- Use HTTPS for all API requests

