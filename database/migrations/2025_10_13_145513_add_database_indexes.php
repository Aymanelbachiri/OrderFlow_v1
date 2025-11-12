<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Create an index if it does not already exist.
     */
    private function createIndexIfNotExists(string $table, string|array $columns): void
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();
        $columnsArray = (array) $columns;
        $indexName = $table . '_' . implode('_', $columnsArray) . '_index';

        if ($driver === 'sqlite') {
            // SQLite supports IF NOT EXISTS for indexes; use raw SQL
            $quotedTable = str_replace("'", "''", $table);
            $quotedColumns = implode(', ', array_map(fn ($c) => '"' . $c . '"', $columnsArray));
            DB::statement("CREATE INDEX IF NOT EXISTS \"{$indexName}\" ON \"{$quotedTable}\" ({$quotedColumns})");
            return;
        }

        // Fallback for other drivers: attempt and ignore if exists
        try {
            Schema::table($table, function (Blueprint $tableBlueprint) use ($columnsArray, $indexName) {
                $tableBlueprint->index($columnsArray, $indexName);
            });
        } catch (\Throwable $e) {
            // Ignore if index already exists
        }
    }

    /**
     * Drop an index if it exists.
     */
    private function dropIndexIfExists(string $table, string|array $columns): void
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();
        $columnsArray = (array) $columns;
        $indexName = $table . '_' . implode('_', $columnsArray) . '_index';

        if ($driver === 'sqlite') {
            // SQLite supports IF EXISTS for drop index
            DB::statement("DROP INDEX IF EXISTS \"{$indexName}\"");
            return;
        }

        try {
            Schema::table($table, function (Blueprint $tableBlueprint) use ($columnsArray, $indexName) {
                $tableBlueprint->dropIndex($indexName);
            });
        } catch (\Throwable $e) {
            // Ignore if missing
        }
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for better query performance (idempotent)

        // Users
        $this->createIndexIfNotExists('users', 'email');
        $this->createIndexIfNotExists('users', 'role');
        $this->createIndexIfNotExists('users', ['role', 'is_active']);
        $this->createIndexIfNotExists('users', 'created_at');

        // Orders
        $this->createIndexIfNotExists('orders', 'user_id');
        $this->createIndexIfNotExists('orders', 'pricing_plan_id');
        $this->createIndexIfNotExists('orders', 'status');
        $this->createIndexIfNotExists('orders', 'expires_at');
        $this->createIndexIfNotExists('orders', ['user_id', 'status']);
        $this->createIndexIfNotExists('orders', ['status', 'expires_at']);
        $this->createIndexIfNotExists('orders', 'created_at');
        $this->createIndexIfNotExists('orders', 'order_number');

        // Payments
        $this->createIndexIfNotExists('payments', 'order_id');
        $this->createIndexIfNotExists('payments', 'status');
        $this->createIndexIfNotExists('payments', 'payment_method');
        $this->createIndexIfNotExists('payments', ['order_id', 'status']);
        $this->createIndexIfNotExists('payments', 'created_at');

        // Pricing plans
        $this->createIndexIfNotExists('pricing_plans', 'is_active');
        $this->createIndexIfNotExists('pricing_plans', 'server_type');
        $this->createIndexIfNotExists('pricing_plans', ['server_type', 'is_active']);
        $this->createIndexIfNotExists('pricing_plans', ['device_count', 'duration_months']);

        // Blog posts
        $this->createIndexIfNotExists('blog_posts', 'author_id');
        $this->createIndexIfNotExists('blog_posts', 'is_published');
        $this->createIndexIfNotExists('blog_posts', 'published_at');
        $this->createIndexIfNotExists('blog_posts', ['is_published', 'published_at']);
        $this->createIndexIfNotExists('blog_posts', 'created_at');
        $this->createIndexIfNotExists('blog_posts', 'slug');

        // Email templates (if table exists)
        if (Schema::hasTable('email_templates')) {
            $this->createIndexIfNotExists('email_templates', 'name');
            $this->createIndexIfNotExists('email_templates', 'is_active');
            $this->createIndexIfNotExists('email_templates', ['name', 'is_active']);
        }

        // System settings
        $this->createIndexIfNotExists('system_settings', 'key');
        $this->createIndexIfNotExists('system_settings', 'type');

        // Renewal notifications (if table exists)
        if (Schema::hasTable('renewal_notifications')) {
            $this->createIndexIfNotExists('renewal_notifications', 'order_id');
            $this->createIndexIfNotExists('renewal_notifications', 'days_before_expiry');
            $this->createIndexIfNotExists('renewal_notifications', 'sent');
            $this->createIndexIfNotExists('renewal_notifications', ['order_id', 'days_before_expiry']);
            $this->createIndexIfNotExists('renewal_notifications', 'sent_at');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes (idempotent)

        // Users
        $this->dropIndexIfExists('users', 'email');
        $this->dropIndexIfExists('users', 'role');
        $this->dropIndexIfExists('users', ['role', 'is_active']);
        $this->dropIndexIfExists('users', 'created_at');

        // Orders
        $this->dropIndexIfExists('orders', 'user_id');
        $this->dropIndexIfExists('orders', 'pricing_plan_id');
        $this->dropIndexIfExists('orders', 'status');
        $this->dropIndexIfExists('orders', 'expires_at');
        $this->dropIndexIfExists('orders', ['user_id', 'status']);
        $this->dropIndexIfExists('orders', ['status', 'expires_at']);
        $this->dropIndexIfExists('orders', 'created_at');
        $this->dropIndexIfExists('orders', 'order_number');

        // Payments
        $this->dropIndexIfExists('payments', 'order_id');
        $this->dropIndexIfExists('payments', 'status');
        $this->dropIndexIfExists('payments', 'payment_method');
        $this->dropIndexIfExists('payments', ['order_id', 'status']);
        $this->dropIndexIfExists('payments', 'created_at');

        // Pricing plans
        $this->dropIndexIfExists('pricing_plans', 'is_active');
        $this->dropIndexIfExists('pricing_plans', 'server_type');
        $this->dropIndexIfExists('pricing_plans', ['server_type', 'is_active']);
        $this->dropIndexIfExists('pricing_plans', ['device_count', 'duration_months']);

        // Blog posts
        $this->dropIndexIfExists('blog_posts', 'author_id');
        $this->dropIndexIfExists('blog_posts', 'is_published');
        $this->dropIndexIfExists('blog_posts', 'published_at');
        $this->dropIndexIfExists('blog_posts', ['is_published', 'published_at']);
        $this->dropIndexIfExists('blog_posts', 'created_at');
        $this->dropIndexIfExists('blog_posts', 'slug');

        // Email templates (if table exists)
        if (Schema::hasTable('email_templates')) {
            $this->dropIndexIfExists('email_templates', 'name');
            $this->dropIndexIfExists('email_templates', 'is_active');
            $this->dropIndexIfExists('email_templates', ['name', 'is_active']);
        }

        // System settings
        $this->dropIndexIfExists('system_settings', 'key');
        $this->dropIndexIfExists('system_settings', 'type');

        // Renewal notifications (if table exists)
        if (Schema::hasTable('renewal_notifications')) {
            $this->dropIndexIfExists('renewal_notifications', 'order_id');
            $this->dropIndexIfExists('renewal_notifications', 'days_before_expiry');
            $this->dropIndexIfExists('renewal_notifications', 'sent');
            $this->dropIndexIfExists('renewal_notifications', ['order_id', 'days_before_expiry']);
            $this->dropIndexIfExists('renewal_notifications', 'sent_at');
        }
    }
};
