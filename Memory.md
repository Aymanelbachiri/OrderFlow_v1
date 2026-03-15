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

- Decision: Agent role with source-based access control
  Reason: Allow admins to create agent users who can only manage data (orders, clients, resellers, trials, analytics) from their assigned sources. Agents cannot access global settings, pricing, credit packs, custom products, sources, WordPress, payment config, or affiliates.
  Date: 2026-03-09

- Decision: Per-source order notification email
  Reason: Each source can optionally receive new order notifications at its own email address (toggle). When enabled, admin notifications for that source go to the source's notify_email using the source's SMTP. When disabled, notifications go to the global admin email using the "main" source SMTP.
  Date: 2026-03-15

- Decision: Generic plan type for pricing plans
  Reason: Allow admins to create plans that are neither "basic" nor "premium" with a custom display name (custom_label) shown in checkout, pricing pages, and emails.
  Date: 2026-03-15

## 4. Database Schema
- Orders table: subscription_username, subscription_password, subscription_url, subscription_m3u_url (legacy), devices (JSON)
- M3U URL is per device: stored when pasted via Fill from M3U, or built from url+username+password. Emails include stored/built M3U link per device.
- Users table: role enum now includes 'agent' (admin, client, reseller, agent)
- agent_source pivot table: user_id (FK), source_id (FK) - links agents to their allowed sources
- Sources table: use_own_notify_email (bool, default false), notify_email (nullable string) - per-source order notification routing
- Pricing Plans table: server_type enum now includes 'generic' (basic, premium, generic); custom_label (nullable string) - display name for generic plans

## 5. API Contracts
- (Web routes for admin order management)

## 6. Conventions
- Naming: Laravel conventions
- Folder structure: app/Http/Controllers/Admin, app/Mail, resources/views/emails

## 7. Known Issues / Constraints
- M3U URL parsing expects format: ...?username=xxx&password=yyy (get.php style)
- SMTP SSL hostname mismatch: When host (e.g. mail.smarters-proiptv.com) uses a cert with different CN (e.g. *.web-hosting.com), set MAIL_VERIFY_PEER=false in .env
- SQLite CHECK constraint: Laravel enum() creates a CHECK constraint in SQLite. The migration 2026_03_15_000001 uses PRAGMA writable_schema to update the constraint to include 'agent'. The migration 2026_03_15_000003 uses the same approach to add 'generic' to pricing_plans.server_type. Must run `php artisan migrate` after pulling.

## 8. Current State
- Subscription activation: each device has its own "Fill from M3U" for per-device URLs
- Emails (client-credentials, account-renewed): M3U link per device, built from device url+username+password
- Order model: getM3uUrl(), buildM3uUrl() helpers
- Order edit: all fields editable; per-device Fill from M3U for subscription credentials
- Renewal activation: Activate button uses existing credentials (no modal); POST to orders.activate-renewal
- Agent management: CRUD at admin.agents.* routes; agents assigned to sources via agent_source pivot
- Source scoping: SourceScopeable trait (app/Traits/SourceScopeable.php) applied to OrderController, ClientController, ResellerController, TrialRequestController, AnalyticsController, DashboardController
- Route split: admin+agent routes (dashboard, clients, orders, resellers, trials, analytics) vs admin-only routes (agents, pricing, credit packs, custom products, sources, affiliates, settings, WordPress, payment config)
- Sidebar: admin-only items hidden for agents via @if(auth()->user()->isAdmin())
- Source notification emails: toggle per source (use_own_notify_email) routes admin order notifications to source's own email or global admin email. Logic centralized in SendPaymentCompletedEmails::sendAdminNotification() and EmailService::resolveAdminRecipientsForOrder()
- Generic pricing plan type: server_type='generic' + custom_label field. Displayed in admin (create/edit/index/show), public pricing page (as separate tab per custom_label), checkout, order views, and emails using server_label accessor.
