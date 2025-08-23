<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\Warehouse;
use Carbon\Carbon;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create sample customers if they don't exist
        $customer = Customer::firstOrCreate([
            'client_name' => 'Sample Customer',
            'email' => 'customer@example.com',
            'contact_no' => '1234567890',
        ]);

        // Use existing warehouse
        $warehouse = Warehouse::first();

        // Create sample sales order if it doesn't exist
        $salesOrder = SalesOrder::firstOrCreate([
            'warehouse_id' => $warehouse->id,
            'customer_group_id' => 1, // Assuming customer group exists
            'status' => 'completed',
        ]);

        // Generate sample invoices for the last 12 months
        $months = [
            ['month' => 1, 'amount' => 45000],
            ['month' => 2, 'amount' => 52000],
            ['month' => 3, 'amount' => 48000],
            ['month' => 4, 'amount' => 61000],
            ['month' => 5, 'amount' => 55000],
            ['month' => 6, 'amount' => 67000],
            ['month' => 7, 'amount' => 72000],
            ['month' => 8, 'amount' => 58000],
            ['month' => 9, 'amount' => 63000],
            ['month' => 10, 'amount' => 69000],
            ['month' => 11, 'amount' => 74000],
            ['month' => 12, 'amount' => 81000],
        ];

        foreach ($months as $monthData) {
            $date = Carbon::now()->subMonths(12 - $monthData['month']);
            
            // Create multiple invoices for each month to simulate real data
            for ($i = 1; $i <= rand(3, 8); $i++) {
                Invoice::create([
                    'warehouse_id' => $warehouse->id,
                    'invoice_number' => 'INV-' . $date->format('Ym') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'customer_id' => $customer->id,
                    'sales_order_id' => $salesOrder->id,
                    'invoice_date' => $date->copy()->addDays(rand(1, 28)),
                    'round_off' => 0,
                    'total_amount' => rand(5000, 15000),
                    'created_at' => $date->copy()->addDays(rand(1, 28)),
                    'updated_at' => $date->copy()->addDays(rand(1, 28)),
                ]);
            }
        }
    }
}
