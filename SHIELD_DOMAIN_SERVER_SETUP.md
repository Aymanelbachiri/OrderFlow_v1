# Shield Domain Server Setup Guide

## Problem
When accessing a shield domain (e.g., `smarterpro-tv.com`), you see the cPanel default page instead of your Laravel application.

## Solution

### Option 1: Add Domain in cPanel (Recommended)

1. **Log into cPanel**
2. **Go to "Addon Domains" or "Parked Domains"**
   - Addon Domains: If you want a separate document root
   - Parked Domains: If you want to use the same document root as your main domain
3. **Add the shield domain:**
   - Domain: `smarterpro-tv.com`
   - Document Root: Point to your Laravel `public` directory
     - Example: `/home/username/public_html/main/public` (adjust path as needed)
4. **Click "Add Domain"**
5. **Wait a few minutes for cPanel to configure Apache**

### Option 2: Configure Apache Virtual Host Manually

If you have SSH access and root/sudo privileges, you can manually configure Apache:

```apache
<VirtualHost *:80>
    ServerName smarterpro-tv.com
    ServerAlias www.smarterpro-tv.com
    
    DocumentRoot /path/to/your/laravel/public
    
    <Directory /path/to/your/laravel/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/smarterpro-tv-error.log
    CustomLog ${APACHE_LOG_DIR}/smarterpro-tv-access.log combined
</VirtualHost>

<VirtualHost *:443>
    ServerName smarterpro-tv.com
    ServerAlias www.smarterpro-tv.com
    
    DocumentRoot /path/to/your/laravel/public
    
    <Directory /path/to/your/laravel/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    SSLEngine on
    SSLCertificateFile /path/to/ssl/certificate.crt
    SSLCertificateKeyFile /path/to/ssl/private.key
    
    ErrorLog ${APACHE_LOG_DIR}/smarterpro-tv-error.log
    CustomLog ${APACHE_LOG_DIR}/smarterpro-tv-access.log combined
</VirtualHost>
```

Then enable the site and restart Apache:
```bash
sudo a2ensite smarterpro-tv.com
sudo systemctl restart apache2
```

### Option 3: Use Wildcard Domain (For Multiple Shield Domains)

If you have many shield domains, you can configure a wildcard virtual host:

```apache
<VirtualHost *:80>
    ServerName *.yourdomain.com
    ServerAlias *.yourdomain.com
    
    DocumentRoot /path/to/your/laravel/public
    
    <Directory /path/to/your/laravel/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Note:** This requires ServerName to support wildcards, which may need special Apache configuration.

### Option 4: Use .htaccess in Root (Temporary Workaround)

If you can't configure cPanel or Apache, you can try adding this to the root `.htaccess`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # If request is for a shield domain, redirect to public
    RewriteCond %{HTTP_HOST} ^(.*\.)?smarterpro-tv\.com$ [NC]
    RewriteCond %{REQUEST_URI} !^/public
    RewriteRule ^(.*)$ /public/$1 [L,QSA]
</IfModule>
```

However, this may not work if cPanel is intercepting the request before it reaches your `.htaccess`.

## Important Notes

1. **SSL Certificates**: After configuring the domain, you'll need SSL certificates. Cloudflare provides free SSL, so ensure your domain is proxied through Cloudflare (orange cloud enabled).

2. **Document Root**: The document root MUST point to the Laravel `public` directory, not the root Laravel directory.

3. **Multiple Shield Domains**: For each shield domain, you'll need to either:
   - Add it in cPanel, OR
   - Configure a separate virtual host, OR
   - Use a wildcard configuration

4. **Testing**: After configuration, test by accessing:
   - `http://smarterpro-tv.com`
   - `https://smarterpro-tv.com` (after SSL is configured)

## Verification

After setup, verify:
1. Domain resolves to your server (DNS check)
2. Domain is configured in cPanel/Apache
3. Document root points to Laravel `public` directory
4. `.htaccess` files are in place
5. Laravel application is accessible

