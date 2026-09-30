<?php

namespace Tests\Feature;

use App\Http\Controllers\ReportController;
use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\SalesOrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CustomerSalesSkuInvoiceDateTest extends TestCase
{
    private function invokeFilter(string $method, ...$arguments)
    {
        return (new \ReflectionMethod(ReportController::class, $method))
            ->invoke(new ReportController, ...$arguments);
    }

    public function test_invoice_range_includes_endpoints_and_uses_creation_date(): void
    {
        DB::statement('CREATE TABLE invoices (id INTEGER, invoice_type TEXT, created_at TEXT, invoice_date TEXT)');
        foreach (['2026-08-31 23:59:59', '2026-09-01 00:00:00', '2026-09-28 23:59:59', '2026-09-29 00:00:00'] as $id => $date) {
            DB::table('invoices')->insert(['id' => $id, 'invoice_type' => 'sales_order', 'created_at' => $date, 'invoice_date' => '2026-08-01']);
        }
        $query = DB::table('invoices');
        $this->invokeFilter('filterSkuInvoiceDates', $query, new Request([
            'invoice_from_date' => '2026-09-01', 'invoice_to_date' => '2026-09-28',
        ]));
        $this->assertSame([1, 2], $query->orderBy('id')->pluck('id')->all());

        $query = DB::table('invoices');
        $this->invokeFilter('filterSkuInvoiceDates', $query, new Request(['invoice_to_date' => '2026-09-01']));
        $this->assertSame([0, 1], $query->orderBy('id')->pluck('id')->all());
    }

    public function test_only_matching_invoices_and_warehouse_allocations_are_kept(): void
    {
        $details = collect();
        foreach ([1 => '2026-08-31', 2 => '2026-09-28'] as $warehouse => $date) {
            $invoice = new Invoice(['invoice_type' => 'sales_order']);
            $invoice->created_at = $date;
            $detail = new InvoiceDetails(['warehouse_id' => $warehouse]);
            $detail->setRelation('invoice', $invoice);
            $details->push($detail);
        }
        $product = new SalesOrderProduct;
        $product->setRelation('invoiceDetails', $details);
        $product->setRelation('warehouseAllocations', collect([(object) ['warehouse_id' => 1], (object) ['warehouse_id' => 2]]));
        $products = collect([$product]);
        $this->invokeFilter('filterSkuLoadedProducts', $products, new Request(['invoice_from_date' => '2026-09-01']));
        $this->assertCount(1, $product->invoiceDetails);
        $this->assertSame(2, $product->warehouseAllocations->first()->warehouse_id);
    }

    public function test_absent_invoice_dates_preserve_existing_query(): void
    {
        $query = DB::table('invoices');
        $original = $query->toSql();
        $this->invokeFilter('filterSkuInvoiceDates', $query, new Request(['from_date' => '2026-09-01']));
        $this->assertSame($original, $query->toSql());
    }
}
