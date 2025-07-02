<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\CustomerGroups;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\PersistRelations;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;

class CustomersImport implements ToModel, WithSkipDuplicates, PersistRelations
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $customers = CustomerGroups::orderBy('id', 'DESC')->first();

        return new Customer([
            //'id' => $row[0],
            'first_name' => $row[1],
            'last_name' => $row[2],
            'email' => $row[3],
            'phone' => $row[4],
            'company_name' => $row[5],
            'gst_number' => $row[6],
            'pan_number' => $row[7],
            'shipping_address' => $row[8],
            'shipping_country' => $row[9],
            'shipping_state' => $row[10],
            'shipping_city' => $row[11],
            'shipping_pincode' => $row[12],
            'billing_address' => $row[13],
            'billing_country' => $row[14],
            'billing_state' => $row[15],
            'billing_city' => $row[16],
            'billing_pincode' => $row[17],
            'status' => '1',
            'group_id' => $customers->id,
        ]);
    }
}
