<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EwayTransportDetail extends Model
{
    //
    protected $fillable = [
        'ewaybill_id',
        'transportation_mode',
        'vehicle_number',
        'transporter_name',
        'transporter_document_number',
        'transporter_document_date',
        'place_of_consignor',
        'state_of_consignor',
    ];
}
