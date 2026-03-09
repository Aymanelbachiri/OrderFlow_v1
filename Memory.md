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

## 3. Architecture Decisions
- Decision: Subscription activation with M3U URL parsing
  Reason: Allow admins to paste M3U URL to auto-fill credentials (url, username, password) or enter manually. All credential emails include M3U link.
  Date: 2026-03-09

## 4. Database Schema
- Orders table: subscription_username, subscription_password, subscription_url, subscription_m3u_url (added 2026-03-09)
- subscription_m3u_url: stores full M3U link when parsed from "Fill from M3U"; otherwise built from url+username+password

## 5. API Contracts
- (Web routes for admin order management)

## 6. Conventions
- Naming: Laravel conventions
- Folder structure: app/Http/Controllers/Admin, app/Mail, resources/views/emails

## 7. Known Issues / Constraints
- M3U URL parsing expects format: ...?username=xxx&password=yyy (get.php style)

## 8. Current State
- Subscription activation: "Fill from M3U" button in popup parses URL and fills url/username/password for all devices
- Emails (client-credentials, account-renewed) include M3U link
- Order model: getM3uUrl(), buildM3uUrl() helpers
