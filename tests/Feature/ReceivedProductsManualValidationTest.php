<?php

namespace Tests\Feature;

use App\Http\Controllers\ReceivedProductsController;
use App\Models\PurchaseOrder;
use Tests\TestCase;

class ReceivedProductsManualValidationTest extends TestCase
{
    public function test_manual_purchase_orders_require_vendor_invoice_no_header_but_auto_orders_do_not(): void
    {
        $controller = new ReceivedProductsController;
        $method = new \ReflectionMethod($controller, 'getRequiredReceivedProductHeaders');
        $mandatoryFieldsMethod = new \ReflectionMethod($controller, 'getMandatoryReceivedProductFields');

        $manualOrder = new PurchaseOrder(['order_type' => 'manual']);
        $autoOrder = new PurchaseOrder(['order_type' => 'auto']);

        $this->assertSame([
            'Vendor Invoice No',
            'Purchase Order No',
            'Vendor SKU Code',
            'Portal Code',
            'Item Code',
            'Title',
            'MRP',
            'PO Quantity',
            'PI Quantity',
            'Quantity Received',
            'Issue Units',
            'Issue Description',
        ], $method->invoke($controller, $manualOrder));

        $this->assertSame([
            'Purchase Order No',
            'Vendor SKU Code',
            'Portal Code',
            'Item Code',
            'Title',
            'MRP',
            'PO Quantity',
            'PI Quantity',
            'Quantity Received',
            'Issue Units',
            'Issue Description',
        ], $method->invoke($controller, $autoOrder));

        $this->assertNotContains('Portal Code', $mandatoryFieldsMethod->invoke($controller, $autoOrder));
        $this->assertNotContains('Item Code', $mandatoryFieldsMethod->invoke($controller, $autoOrder));
        $this->assertContains('Vendor Invoice No', $mandatoryFieldsMethod->invoke($controller, $manualOrder));
    }

    public function test_auto_purchase_orders_do_not_access_vendor_invoice_no_key(): void
    {
        $purchaseOrder = new PurchaseOrder(['order_type' => 'auto']);
        $record = [
            'Purchase Order No' => 'PO-1001',
            'Vendor SKU Code' => 'SKU-1',
            'Portal Code' => 'Vendor-1',
            'Item Code' => 'ITEM-1',
            'Title' => 'Test title',
            'MRP' => '100',
            'PO Quantity' => '5',
            'PI Quantity' => '5',
            'Quantity Received' => '3',
            'Issue Units' => '',
            'Issue Description' => '',
        ];

        $this->assertSame('', $purchaseOrder->order_type === 'manual' ? ($record['Vendor Invoice No'] ?? '') : '');
        $this->assertArrayNotHasKey('Vendor Invoice No', $record);
    }
}
