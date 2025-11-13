# Multi-Admin System Implementation Plan

## Overview
Implement a multi-admin system where:
- One **Super Admin** has access to all data and can manage all admins
- Multiple **Regular Admins** with isolated data and configurable permissions
- Each admin has their own configuration (payment, pricing, SMTP, etc.)
- Super admin can set limits per admin (sources, custom products, etc.)

## Database Schema Changes

### 1. Admin Users Table
- Extend User model or create separate Admin model
- Add `admin_id` to User table (nullable, for admin users)
- Add `is_super_admin` boolean flag
- Add `created_by_admin_id` (who created this admin)

### 2. Admin Permissions Table
- `admin_id` (foreign key)
- `can_manage_sources` (boolean)
- `can_create_custom_products` (boolean)
- `can_send_renewal_emails` (boolean)
- `max_sources` (integer, nullable)
- `max_custom_products` (integer, nullable)
- Other permission flags

### 3. Admin Configs Table
- `admin_id` (foreign key)
- Payment settings (JSON)
- SMTP settings (JSON or separate fields)
- Other admin-specific configurations

### 4. Add admin_id to existing tables:
- `orders` table
- `payment_intents` table
- `sources` table
- `custom_products` table
- `reseller_credit_packs` table
- `pricing_plans` table
- `system_settings` table (admin-specific settings)

## Models to Create/Modify

### New Models:
1. **Admin** (or extend User)
2. **AdminPermission**
3. **AdminConfig**
4. **AdminLimit**

### Models to Modify:
1. **User** - Add admin relationship
2. **Order** - Add admin_id, scope to admin
3. **PaymentIntent** - Add admin_id
4. **Source** - Add admin_id, enforce limits
5. **CustomProduct** - Add admin_id, enforce limits
6. **PricingPlan** - Add admin_id
7. **ResellerCreditPack** - Add admin_id
8. **SystemSetting** - Support admin-specific settings

## Middleware & Authorization

1. **SuperAdminMiddleware** - Check if user is super admin
2. **AdminPermissionMiddleware** - Check admin permissions
3. **AdminDataScopeMiddleware** - Automatically scope queries to admin's data

## Controllers

1. **SuperAdminController** - Manage admins, view all data
2. **AdminController** - Regular admin operations
3. **AdminManagementController** - CRUD for admins (super admin only)
4. **AdminConfigController** - Manage admin configurations

## Features

### Super Admin:
- View all admins
- Create/edit/delete admins
- Set admin permissions
- Set admin limits (sources, products, etc.)
- View all orders from all admins
- View all data from all admins
- Override any admin configuration

### Regular Admin:
- View only their own data
- Manage their own configuration
- Create sources (up to limit)
- Create custom products (up to limit)
- Send renewal emails (if permitted)
- Manage their own pricing plans
- Use their own SMTP settings

## Implementation Steps

1. ✅ Create feature branch
2. Create database migrations
3. Create models
4. Update existing models
5. Create middleware
6. Create controllers
7. Create views
8. Update routes
9. Add data scoping
10. Add permission checks
11. Testing

