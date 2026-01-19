<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite doesn't support modifying enums, so we need to recreate the table
        // For SQLite, we'll use a raw approach
        if (DB::getDriverName() === 'sqlite') {
            // Disable foreign key checks
            DB::statement('PRAGMA foreign_keys=off;');
            
            // Create a new table with the updated enum
            DB::statement('
                CREATE TABLE custom_products_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name VARCHAR(255) NOT NULL,
                    slug VARCHAR(255) NOT NULL UNIQUE,
                    short_description TEXT,
                    description TEXT,
                    price DECIMAL(10, 2) NOT NULL,
                    product_type VARCHAR(255) CHECK(product_type IN (\'service\', \'digital\', \'hotplayer_activation\', \'other\')) DEFAULT \'service\',
                    is_active BOOLEAN DEFAULT 1,
                    allow_direct_checkout BOOLEAN DEFAULT 0,
                    stock_quantity INTEGER,
                    metadata TEXT,
                    custom_fields TEXT,
                    created_at TIMESTAMP,
                    updated_at TIMESTAMP
                )
            ');
            
            // Copy data from old table
            DB::statement('
                INSERT INTO custom_products_new (id, name, slug, short_description, description, price, product_type, is_active, allow_direct_checkout, stock_quantity, metadata, custom_fields, created_at, updated_at)
                SELECT id, name, slug, short_description, description, price, product_type, is_active, allow_direct_checkout, stock_quantity, metadata, custom_fields, created_at, updated_at
                FROM custom_products
            ');
            
            // Drop old table
            DB::statement('DROP TABLE custom_products');
            
            // Rename new table
            DB::statement('ALTER TABLE custom_products_new RENAME TO custom_products');
            
            // Re-enable foreign key checks
            DB::statement('PRAGMA foreign_keys=on;');
        } else {
            // For MySQL/PostgreSQL, we can modify the column directly
            DB::statement("ALTER TABLE custom_products MODIFY COLUMN product_type ENUM('service', 'digital', 'hotplayer_activation', 'other') DEFAULT 'service'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=off;');
            
            DB::statement('
                CREATE TABLE custom_products_new (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name VARCHAR(255) NOT NULL,
                    slug VARCHAR(255) NOT NULL UNIQUE,
                    short_description TEXT,
                    description TEXT,
                    price DECIMAL(10, 2) NOT NULL,
                    product_type VARCHAR(255) CHECK(product_type IN (\'service\', \'digital\', \'other\')) DEFAULT \'service\',
                    is_active BOOLEAN DEFAULT 1,
                    allow_direct_checkout BOOLEAN DEFAULT 0,
                    stock_quantity INTEGER,
                    metadata TEXT,
                    custom_fields TEXT,
                    created_at TIMESTAMP,
                    updated_at TIMESTAMP
                )
            ');
            
            // Copy data, converting hotplayer_activation to 'other'
            DB::statement('
                INSERT INTO custom_products_new (id, name, slug, short_description, description, price, product_type, is_active, allow_direct_checkout, stock_quantity, metadata, custom_fields, created_at, updated_at)
                SELECT id, name, slug, short_description, description, price, 
                    CASE WHEN product_type = \'hotplayer_activation\' THEN \'other\' ELSE product_type END,
                    is_active, allow_direct_checkout, stock_quantity, metadata, custom_fields, created_at, updated_at
                FROM custom_products
            ');
            
            DB::statement('DROP TABLE custom_products');
            DB::statement('ALTER TABLE custom_products_new RENAME TO custom_products');
            DB::statement('PRAGMA foreign_keys=on;');
        } else {
            DB::statement("ALTER TABLE custom_products MODIFY COLUMN product_type ENUM('service', 'digital', 'other') DEFAULT 'service'");
        }
    }
};
