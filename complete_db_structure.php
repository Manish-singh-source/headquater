# Comprehensive Warehouse Management Database Schema with Laravel Migrations

## Table of Contents
1. Master Data Tables
2. Inventory Management Tables
3. Order Management Tables
4. Invoice & Billing Tables
5. Return & Issue Management Tables
6. Additional Supporting Tables

---

## MASTER DATA TABLES

### 1. Warehouses Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('warehouse_code', 50)->unique();
            $table->string('warehouse_name', 255);
            $table->text('location_address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('pincode', 10)->nullable();
            $table->string('manager_name', 255)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->index('warehouse_code');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
```

### 2. Users Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->string('phone_number', 20)->nullable();
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->enum('role', ['admin', 'warehouse_manager', 'sales_team', 'vendor_manager', 'viewer'])->default('viewer');
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->index('warehouse_id');
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
```

### 3. Product Categories Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->text('description')->nullable();
            $table->foreignId('parent_category_id')->nullable()->constrained('product_categories')->onDelete('set null');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
```

### 4. Products Table (CORRECTED)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('restrict');
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->onDelete('set null');
            $table->string('sku', 100)->unique();
            $table->string('ean_code', 20)->nullable();
            $table->string('brand', 100)->nullable();
            $table->string('hsn_code', 20)->required();
            $table->text('description')->nullable();
            $table->decimal('mrp', 12, 2);
            $table->decimal('gst_percentage', 5, 2);
            $table->integer('pcs_per_set')->nullable();
            $table->integer('sets_per_carton')->nullable();
            $table->integer('case_pack_quantity')->nullable();
            $table->decimal('basic_rate', 12, 2);
            $table->decimal('net_landing_rate', 12, 2);
            $table->integer('shelf_life_days')->nullable();
            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->index('sku');
            $table->index('ean_code');
            $table->index('warehouse_id');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

### 5. Customers Table (CORRECTED)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('restrict');
            $table->string('facility_name', 255)->nullable();
            $table->string('client_name', 255);
            $table->string('contact_name', 255)->nullable();
            $table->string('email', 255)->unique()->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('company_name', 255)->nullable();
            $table->string('gstin', 15)->unique()->nullable();
            $table->enum('gst_treatment', ['regular', 'unregistered', 'exempted', 'composition', 'consumer'])->default('regular');
            $table->string('pan_number', 20)->nullable();
            $table->json('private_details')->nullable();
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->index('client_name');
            $table->index('email');
            $table->index('gstin');
            $table->index('warehouse_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
```

### 6. Customer Addresses Table (NEW)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->enum('address_type', ['billing', 'shipping', 'delivery'])->default('billing');
            $table->string('address_line1', 255);
            $table->string('address_line2', 255)->nullable();
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('country', 100);
            $table->string('pincode', 10);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->unique(['customer_id', 'address_type', 'is_default']);
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
```

### 7. Vendors Table (CORRECTED)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('restrict');
            $table->string('vendor_code', 50)->unique();
            $table->string('vendor_name', 255);
            $table->string('contact_name', 255)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('gstin', 15)->unique()->nullable();
            $table->enum('gst_treatment', ['regular', 'unregistered', 'exempted', 'composition'])->default('regular');
            $table->string('pan_number', 20)->nullable();
            $table->string('payment_terms', 100)->nullable();
            $table->decimal('credit_limit', 12, 2)->nullable();
            $table->enum('status', ['active', 'inactive', 'blocked', 'under_review'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->index('vendor_code');
            $table->index('vendor_name');
            $table->index('warehouse_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
```

### 8. Vendor Addresses Table (NEW)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->enum('address_type', ['billing', 'shipping', 'warehouse', 'factory'])->default('billing');
            $table->string('address_line1', 255);
            $table->string('address_line2', 255)->nullable();
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('country', 100);
            $table->string('pincode', 10);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['vendor_id', 'address_type', 'is_default']);
            $table->index('vendor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_addresses');
    }
};
```

### 9. Customer Groups Table (CORRECTED)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('restrict');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['warehouse_id', 'name']);
            $table->index('warehouse_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_groups');
    }
};
```

### 10. Customer Group Members Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_group_id')->constrained('customer_groups')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['customer_group_id', 'customer_id']);
            $table->index('customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_group_members');
    }
};
```

### 11. SKU Mappings Table (CORRECTED)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sku_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->onDelete('set null');
            $table->string('vendor_sku', 100)->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->string('customer_sku', 100)->nullable();
            $table->enum('mapping_status', ['active', 'inactive', 'discontinued'])->default('active');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['product_id', 'vendor_id', 'customer_id']);
            $table->index('vendor_sku');
            $table->index('customer_sku');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sku_mappings');
    }
};
```

---

## INVENTORY MANAGEMENT TABLES

### 12. Warehouse Stocks Table (CORRECTED)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('restrict');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->integer('original_quantity')->default(0);
            $table->integer('available_quantity')->default(0);
            $table->integer('block_quantity')->default(0);
            $table->integer('reserved_quantity')->default(0);
            $table->date('expiry_date')->nullable();
            $table->string('batch_number', 100)->nullable();
            $table->timestamp('last_received_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['warehouse_id', 'product_id', 'batch_number']);
            $table->index('warehouse_id');
            $table->index('product_id');
            $table->index('expiry_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_stocks');
    }
};
```

### 13. Warehouse Stock Logs Table (CORRECTED)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_stock_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('restrict');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->enum('transaction_type', [
                'received', 'sold', 'returned', 'adjusted', 
                'blocked', 'unblocked', 'reserved', 'unreserved'
            ]);
            $table->integer('quantity_changed');
            $table->string('reason', 255);
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('created_at')->useCurrent();
            $table->index('reference_type');
            $table->index('warehouse_id');
            $table->index('product_id');
            $table->index('transaction_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_stock_logs');
    }
};
```

---

## ORDER MANAGEMENT TABLES

### 14. Sales Orders Table (CORRECTED)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('restrict');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('restrict');
            $table->foreignId('customer_group_id')->nullable()->constrained('customer_groups')->onDelete('set null');
            $table->string('order_number', 50)->unique();
            $table->string('po_number', 100)->nullable();
            $table->date('order_date');
            $table->date('required_delivery_date')->nullable();
            $table->integer('total_quantity')->nullable();
            $table->decimal('subtotal_amount', 14, 2)->nullable();
            $table->decimal('tax_amount', 14, 2)->nullable();
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->nullable();
            $table->enum('status', [
                'draft', 'confirmed', 'partially_dispatched', 
                'dispatched', 'completed', 'cancelled'
            ])->default('draft');
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            $table->index('order_number');
            $table->index('customer_id');
            $table->index('order_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_orders');
    }
};
```

### 15. Sales Order Products Table (CORRECTED)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained('sales_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->string('sku', 100);
            $table->integer('ordered_quantity');
            $table->enum('dispatch_unit', ['pieces', 'sets', 'cartons'])->default('pieces');
            $table->integer('dispatched_quantity')->default(0);
            $table->integer('returned_quantity')->default(0);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('discount_per_unit', 12, 2)->default(0);
            $table->decimal('line_total', 14, 2)->nullable();
            $table->decimal('tax_percentage', 5, 2)->nullable();
            $table->decimal('tax_amount', 14, 2)->nullable();
            $table->decimal('gross_line_total', 14, 2)->nullable();
            $table->enum('status', [
                'pending', 'confirmed', 'partially_dispatched', 
                'dispatched', 'cancelled'
            ])->default('pending');
            $table->enum('issue_status', ['none', 'pending', 'in_progress', 'resolved', 'rejected'])->default('none');
            $table->timestamps();
            $table->softDeletes();
            $table->index('sales_order_id');
            $table->index('product_id');
            $table->index('sku');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_order_products');
    }
};
```

### 16. Order Item Issues Table (NEW)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_item_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_product_id')->constrained('sales_order_products')->onDelete('cascade');
            $table->enum('issue_type', [
                'quality', 'quantity_mismatch', 'damage', 
                'missing_item', 'delayed', 'wrong_item', 'other'
            ]);
            $table->text('description');
            $table->timestamp('reported_at')->useCurrent();
            $table->foreignId('reported_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('resolution')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'rejected'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
            $table->index('sales_order_product_id');
            $table->index('issue_type');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item_issues');
    }
};
```

### 17. Warehouse Allocations Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained('sales_orders')->onDelete('cascade');
            $table->foreignId('sales_order_product_id')->constrained('sales_order_products')->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('restrict');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->string('sku', 100);
            $table->integer('allocated_quantity');
            $table->integer('final_dispatched_quantity')->default(0);
            $table->integer('box_count')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->integer('sequence')->nullable();
            $table->enum('status', ['allocated', 'packed', 'shipped', 'delivered', 'failed'])->default('allocated');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('sales_order_id');
            $table->index('warehouse_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_allocations');
    }
};
```

---

## PURCHASE ORDER TABLES

### 18. Purchase Orders Table (CORRECTED)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('restrict');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('restrict');
            $table->foreignId('sales_order_id')->nullable()->constrained('sales_orders')->onDelete('set null');
            $table->string('po_number', 50)->unique();
            $table->date('order_date');
            $table->date('expected_delivery_date')->nullable();
            $table->date('actual_delivery_date')->nullable();
            $table->decimal('subtotal_amount', 14, 2)->nullable();
            $table->decimal('tax_amount', 14, 2)->nullable();
            $table->decimal('total_amount', 14, 2)->nullable();
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->decimal('due_amount', 14, 2)->nullable();
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->string('payment_terms', 100)->nullable();
            $table->enum('status', [
                'draft', 'confirmed', 'partially_received', 
                'received', 'completed', 'cancelled'
            ])->default('draft');
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            $table->index('po_number');
            $table->index('vendor_id');
            $table->index('order_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
```

### 19. Purchase Order Products Table (CORRECTED)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_order_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->string('sku', 100)->nullable();
            $table->string('vendor_sku', 100)->nullable();
            $table->integer('ordered_quantity');
            $table->integer('received_quantity')->default(0);
            $table->integer('rejected_quantity')->default(0);
            $table->decimal('unit_cost', 12, 2);
            $table->decimal('discount_per_unit', 12, 2)->default(0);
            $table->decimal('line_total', 14, 2)->nullable();
            $table->decimal('tax_percentage', 5, 2)->nullable();
            $table->decimal('tax_amount', 14, 2)->nullable();
            $table->decimal('gross_line_total', 14, 2)->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->enum('status', [
                'pending', 'partially_received', 'received', 
                'rejected', 'cancelled'
            ])->default('pending');
            $table->timestamps();
            $table->softDeletes();
            $table->index('purchase_order_id');
            $table->index('product_id');
            $table->index('sku');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_products');
    }
};
```

### 20. Purchase GRN (Goods Receipt Note) Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_grns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('restrict');
            $table->string('grn_number', 50)->unique();
            $table->date('grn_date');
            $table->text('grn_file')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'received', 'verified', 'archived'])->default('pending');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            $table->index('grn_number');
            $table->index('purchase_order_id');
            $table->index('grn_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_grns');
    }
};
```

---

## INVOICE & BILLING TABLES

### 21. Vendor Proforma Invoices Table (CORRECTED)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_proforma_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('restrict');
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('restrict');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('restrict');
            $table->string('pi_number', 100)->unique();
            $table->date('pi_date');
            $table->decimal('subtotal_amount', 14, 2)->nullable();
            $table->decimal('tax_amount', 14, 2)->nullable();
            $table->decimal('total_amount', 14, 2)->nullable();
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->decimal('due_amount', 14, 2)->nullable();
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('approval_reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'active', 'cancelled'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->index('pi_number');
            $table->index('vendor_id');
            $table->index('pi_date');
            $table->index('approval_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_proforma_invoices');
    }
};
```

### 22. Vendor Proforma Invoice Products Table (CORRECTED)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_proforma_invoice_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_proforma_invoice_id')->constrained('vendor_proforma_invoices')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->string('vendor_sku', 100)->nullable();
            $table->string('product_name', 255)->nullable();
            $table->integer('quantity_required')->nullable();
            $table->integer('quantity_available')->nullable();
            $table->integer('quantity_received')->default(0);
            $table->integer('quantity_rejected')->default(0);
            $table->decimal('purchase_rate', 12, 2);
            $table->decimal('discount_per_unit', 12, 2)->default(0);
            $table->decimal('line_total', 14, 2)->nullable();
            $table->decimal('gst_percentage', 5, 2)->nullable();
            $table->string('hsn_code', 20)->nullable();
            $table->enum('delivery_status', ['pending', 'partial', 'delivered'])->default('pending');
            $table->enum('quality_status', ['pending', 'accepted', 'rejected', 'under_review'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
            $table->index('vendor_proforma_invoice_id');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_proforma_invoice_products');
    }
};
```

### 23. Customer Invoices Table (CORRECTED)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('restrict');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('restrict');
            $table->foreignId('sales_order_id')->nullable()->constrained('sales_orders')->onDelete('set null');
            $table->string('invoice_number', 50)->unique();
            $table->date('invoice_date');
            $table->string('po_number', 100)->nullable();
            $table->date('po_date')->nullable();
            $table->decimal('subtotal', 14, 2)->nullable();
            $table->decimal('tax_amount', 14, 2)->nullable();
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('round_off', 14, 2)->nullable();
            $table->decimal('total_amount', 14, 2)->nullable();
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->decimal('balance_due', 14, 2)->nullable();
            $table->enum('payment_mode', ['cash', 'check', 'bank_transfer', 'credit', 'upi'])->nullable();
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
            $table->enum('invoice_type', ['tax', 'non_tax'])->default('tax');
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'issued', 'paid', 'cancelled'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->index('invoice_number');
            $table->index('customer_id');
            $table->index('invoice_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
```

### 24. Invoice Line Items Table (NEW)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->string('sku', 100);
            $table->string('product_name', 255)->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('discount_per_unit', 12, 2)->default(0);
            $table->decimal('line_total', 14, 2);
            $table->decimal('gst_percentage', 5, 2)->nullable();
            $table->decimal('gst_amount', 14, 2)->nullable();
            $table->decimal('gross_amount', 14, 2)->nullable();
            $table->string('hsn_code', 20)->nullable();
            $table->timestamps();
            $table->index('invoice_id');
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_line_items');
    }
};
```

### 25. Delivery Challan Table (NEW - REQUIRED FOR INDIA)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_chalkans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('restrict');
            $table->foreignId('sales_order_id')->constrained('sales_orders')->onDelete('restrict');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->string('challan_number', 50)->unique();
            $table->date('challan_date');
            $table->text('delivery_address')->nullable();
            $table->string('delivery_city', 100)->nullable();
            $table->string('delivery_state', 100)->nullable();
            $table->string('delivery_pincode', 10)->nullable();
            $table->string('driver_name', 255)->nullable();
            $table->string('vehicle_number', 50)->nullable();
            $table->decimal('total_weight', 10, 2)->nullable();
            $table->integer('total_packages')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->date('actual_delivery_date')->nullable();
            $table->enum('status', ['generated', 'dispatched', 'in_transit', 'delivered', 'returned'])->default('generated');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('challan_number');
            $table->index('sales_order_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_chalkans');
    }
};
```

### 26. E-Invoices Table (NEW - GST COMPLIANCE)

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('e_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('restrict');
            $table->string('irn', 64)->unique()->nullable();
            $table->string('ack_no', 50)->nullable();
            $table->timestamp('ack_date')->nullable();
            $table->longText('signed_invoice')->nullable();
            $table->longText('signed_qr_code')->nullable();
            $table->text('pdf_url')->nullable();
            $table->enum('status', ['pending', 'generated', 'cancelled', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('irn');
            $table->index('invoice_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('e_invoices');
    }
};
```

---

## PAYMENT TABLES

### 27. Vendor Payments Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_pi_id')->constrained('vendor_proforma_invoices')->onDelete('restrict');
            $table->decimal('amount', 14, 2);
            $table->string('payment_utr_no', 100)->nullable();
            $table->enum('payment_method', ['bank_transfer', 'check', 'cash', 'credit_card', 'wallet'])->nullable();
            $table->enum('payment_status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->date('payment_date');
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            $table->index('vendor_pi_id');
            $table->index('payment_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_payments');
    }
};
```

### 28. Customer Payments Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('restrict');
            $table->decimal('amount', 14, 2);
            $table->string('payment_utr_no', 100)->nullable();
            $table->enum('payment_method', ['bank_transfer', 'check', 'cash', 'credit_card', 'upi'])->nullable();
            $table->enum('payment_status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->date('payment_date');
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            $table->index('invoice_id');
            $table->index('payment_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_payments');
    }
};
```

---

## RETURN & ISSUE MANAGEMENT TABLES

### 29. Vendor Return Products Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_return_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_proforma_invoice_product_id')->constrained('vendor_proforma_invoice_products')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->string('sku', 100);
            $table->integer('return_quantity');
            $table->enum('return_reason', [
                'damaged', 'defective', 'expired', 'wrong_item', 
                'excess_delivery', 'quality_issue', 'other'
            ]);
            $table->text('return_description')->nullable();
            $table->enum('return_status', ['pending', 'approved', 'received', 'rejected'])->default('pending');
            $table->date('return_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('vendor_proforma_invoice_product_id');
            $table->index('product_id');
            $table->index('return_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_return_products');
    }
};
```

### 30. Customer Return Products Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('restrict');
            $table->foreignId('sales_order_id')->constrained('sales_orders')->onDelete('restrict');
            $table->foreignId('sales_order_product_id')->constrained('sales_order_products')->onDelete('restrict');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->string('sku', 100);
            $table->integer('return_quantity');
            $table->enum('return_reason', [
                'damaged', 'defective', 'expired', 'wrong_item', 
                'not_needed', 'quality_issue', 'other'
            ]);
            $table->text('return_description')->nullable();
            $table->enum('return_status', ['initiated', 'approved', 'received', 'rejected'])->default('initiated');
            $table->date('return_date')->nullable();
            $table->string('return_reference_no', 100)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('sales_order_id');
            $table->index('product_id');
            $table->index('return_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_returns');
    }
};
```

### 31. Product Issues Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->onDelete('set null');
            $table->foreignId('vendor_proforma_invoice_id')->nullable()->constrained('vendor_proforma_invoices')->onDelete('set null');
            $table->foreignId('vendor_proforma_invoice_product_id')->nullable()->constrained('vendor_proforma_invoice_products')->onDelete('set null');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->string('vendor_sku_code', 100)->nullable();
            $table->integer('quantity_requirement')->nullable();
            $table->integer('available_quantity')->nullable();
            $table->integer('quantity_received')->nullable();
            $table->enum('issue_type', [
                'quality', 'quantity_mismatch', 'damage', 
                'missing_item', 'expired', 'wrong_item', 'other'
            ]);
            $table->text('issue_description')->nullable();
            $table->enum('issue_from', ['vendor', 'warehouse', 'customer']);
            $table->enum('issue_status', ['reported', 'investigating', 'resolved', 'rejected'])->default('reported');
            $table->text('resolution')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('purchase_order_id');
            $table->index('product_id');
            $table->index('issue_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_issues');
    }
};
```

### 32. Warehouse Product Issues Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouse_product_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->constrained('warehouses')->onDelete('restrict');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('sales_order_id')->nullable()->constrained('sales_orders')->onDelete('set null');
            $table->foreignId('sales_order_product_id')->nullable()->constrained('sales_order_products')->onDelete('set null');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->string('sku', 100);
            $table->enum('issue_type', [
                'damaged_in_warehouse', 'expiry_approaching', 'stock_mismatch', 
                'wrong_placement', 'missing', 'other'
            ]);
            $table->text('issue_description')->nullable();
            $table->enum('issue_from', ['warehouse', 'received', 'dispatch']);
            $table->enum('issue_status', ['reported', 'investigating', 'resolved', 'rejected'])->default('reported');
            $table->text('resolution')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('warehouse_id');
            $table->index('sales_order_id');
            $table->index('product_id');
            $table->index('issue_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_product_issues');
    }
};
```

---

## SUPPORTING TABLES

### 33. Debit Notes (DN) Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debit_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('restrict');
            $table->string('dn_number', 50)->unique();
            $table->date('dn_date');
            $table->decimal('dn_amount', 14, 2);
            $table->enum('dn_reason', [
                'return', 'damage', 'shortage', 'adjustment', 'other'
            ]);
            $table->text('dn_receipt')->nullable();
            $table->enum('status', ['draft', 'issued', 'applied', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('dn_number');
            $table->index('invoice_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debit_notes');
    }
};
```

### 34. Appointment Tracking Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('restrict');
            $table->datetime('appointment_date_time');
            $table->string('contact_person', 255)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->text('special_instructions')->nullable();
            $table->enum('status', ['scheduled', 'confirmed', 'completed', 'rescheduled', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('invoice_id');
            $table->index('appointment_date_time');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_appointments');
    }
};
```

### 35. POD (Proof of Delivery) Files Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pod_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->string('file_path', 500);
            $table->string('file_name', 255);
            $table->string('file_type', 20)->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('invoice_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pod_files');
    }
};
```

### 36. GRN Files Table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grn_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_grn_id')->constrained('purchase_grns')->onDelete('cascade');
            $table->string('file_path', 500);
            $table->string('file_name', 255);
            $table->string('file_type', 20)->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('purchase_grn_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grn_files');
    }
};
```

---

## NOTES

1. **Soft Deletes**: Use `->whereNull('deleted_at')` or `->active()` scope in queries
2. **All timestamps** use UTC timezone
3. **Decimal fields** use DECIMAL(14,2) for precision with currency
4. **Foreign keys** use CASCADE DELETE where appropriate; SET NULL for optional
5. **Indexes** included on frequently queried columns
6. **Character set**: UTF-8MB4 for multilingual support
7. **India Compliance**: E-invoices, Delivery Challan, GST fields included
8. **Multi-warehouse**: Every table has warehouse_id except master/config tables

## MIGRATION ORDER

Create migrations in this sequence:

1. warehouses
2. users
3. product_categories
4. products
5. customers & customer_addresses
6. vendors & vendor_addresses
7. customer_groups & customer_group_members
8. sku_mappings
9. warehouse_stocks & warehouse_stock_logs
10. sales_orders
11. sales_order_products & order_item_issues
12. warehouse_allocations
13. purchase_orders & purchase_order_products
14. purchase_grns & grn_files
15. vendor_proforma_invoices & vendor_proforma_invoice_products
16. vendor_payments & vendor_returns
17. invoices & invoice_line_items
18. e_invoices
19. delivery_chalkans
20. customer_payments & debit_notes
21. delivery_appointments & pod_files
22. product_issues & warehouse_product_issues
23. customer_returns

