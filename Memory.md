# Project Memory

## 1. Project Overview
- Purpose: IPTV order management and subscription platform (OrderFlow)
- Tech stack: Laravel 12, PHP 8.2+, Stripe, PayPal, Blade
- Architecture style: MVC, service layer for payments/emails

## 2. Environment
- Runtime: PHP 8.2+
- Package manager: Composer
- Key dependencies: Laravel, Stripe, PayPal, Spatie packages
- Environment variables: DB_*, MAIL_*, STRIPE_*, PAYPAL_*, etc.
- MAIL_VERIFY_PEER: set to `false` when SMTP host uses a certificate with different CN (e.g. shared hosting with *.web-hosting.com)

## 3. Architecture Decisions
- Decision: Subscription activation with M3U URL parsing
  Reason: Allow admins to paste M3U URL to auto-fill credentials (url, username, password) or enter manually. All credential emails include M3U link.
  Date: 2026-03-09

## 4. Database Schema
- Orders table: subscription_username, subscription_password, subscription_url, subscription_m3u_url (legacy), devices (JSON)
- M3U URL is per device: stored when pasted via Fill from M3U, or built from url+username+password. Emails include stored/built M3U link per device.

## 5. API Contracts
- (Web routes for admin order management)

## 6. Conventions
- Naming: Laravel conventions
- Folder structure: app/Http/Controllers/Admin, app/Mail, resources/views/emails

## 7. Known Issues / Constraints
- M3U URL parsing expects format: ...?username=xxx&password=yyy (get.php style)
- SMTP SSL hostname mismatch: When host (e.g. mail.smarters-proiptv.com) uses a cert with different CN (e.g. *.web-hosting.com), set MAIL_VERIFY_PEER=false in .env

## 8. Current State
- Subscription activation: "Fill All" fills all devices from one M3U; each device has its own "Fill from M3U" for per-device URLs
- Emails (client-credentials, account-renewed): M3U link per device, built from device url+username+password
- Order model: getM3uUrl(), buildM3uUrl() helpers
- Order edit: all fields editable; per-device Fill from M3U for subscription credentials
