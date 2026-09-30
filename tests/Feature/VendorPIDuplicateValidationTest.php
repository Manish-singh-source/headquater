<?php

namespace Tests\Feature;

use App\Http\Controllers\PurchaseOrderController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class VendorPIDuplicateValidationTest extends TestCase
{
    public function test_matching_vendor_pi_item_is_only_duplicate_within_the_same_purchase_order(): void
    {
        Schema::create('vendor_p_i_products', function ($table): void {
            $table->id();
            $table->string('purchase_order_id')->nullable();
            $table->string('vendor_pi_id')->nullable();
            $table->string('portal_code')->nullable();
            $table->string('item_code')->nullable();
            $table->string('vendor_invoice_no')->nullable();
            $table->string('vendor_sku_code')->nullable();
        });

        Schema::create('sku_mappings', function ($table): void {
            $table->id();
            $table->string('vendor_sku');
            $table->string('product_sku')->nullable();
        });

        DB::table('vendor_p_i_products')->insert([
            'purchase_order_id' => 320,
            'vendor_pi_id' => 43,
            'portal_code' => 'Blinkit',
            'item_code' => '10322904',
            'vendor_invoice_no' => 'OPS/2025/2605-HY1526/26-27',
            'vendor_sku_code' => 'B25613',
        ]);

        $rows = [[
            'Portal Code' => 'Blinkit',
            'Item Code' => '10322904',
            'Vendor Invoice No' => 'OPS/2025/2605-HY1526/26-27',
            'Vendor SKU Code' => 'B25613',
        ]];

        $validateDuplicates = new \ReflectionMethod(PurchaseOrderController::class, 'validateDuplicateVendorPIProducts');

        $this->assertNull($validateDuplicates->invoke(new PurchaseOrderController, $rows, 323));
        $this->assertStringContainsString('Purchase Order #320', $validateDuplicates->invoke(new PurchaseOrderController, $rows, 320));
    }
}
