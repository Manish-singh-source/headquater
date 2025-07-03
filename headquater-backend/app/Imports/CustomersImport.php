<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\CustomerGroups;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\PersistRelations;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;

class CustomersImport implements ToModel, WithSkipDuplicates, PersistRelations, WithBatchInserts, WithHeadingRow, WithChunkReading, ShouldQueue
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    use Importable;
    
    public function model(array $row)
    {
        $group = CustomerGroups::latest()->first();
        if (!$group) {
            // Optional: throw an exception or create a default group
            throw new \Exception("Customer group not found.");
        }

        $name_parts = explode(' ', $row['contact_name']);

        return new Customer([
            'first_name' => $name_parts[0],
            'last_name' => $name_parts[1] ?? '',
            'first_name' => $row['contact_name'],
            'last_name' => $row['contact_name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'company_name' => $row['client_name'],
            'gst_number' => $row['gstin'],
            'pan_number' => $row['pan'],
            'shipping_address' => $row['shipping_address'],
            'shipping_country' => $row['shipping_country'],
            'shipping_state' => $row['shipping_state'],
            'shipping_city' => $row['shipping_city'],
            'shipping_pincode' => $row['shipping_zip'],
            'billing_address' => $row['billing_address'],
            'billing_country' => $row['billing_country'],
            'billing_state' => $row['billing_state'],
            'billing_city' => $row['billing_city'],
            'billing_pincode' => $row['billing_zip'],
            'status' => isset($row['status']) ? (int) $row['status'] : 1,
            'group_id' => $group->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function batchSize(): int
    {
        return 50;
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
            ],
        ];
    }
}
