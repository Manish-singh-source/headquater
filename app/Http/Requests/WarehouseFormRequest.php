<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3',
            'type' => 'required|in:storage hub,return center',
            'contact_person_name' => 'required|min:3',
            'contact_person_phone_no' => 'required|digits:10',
            'contact_person_alt_phone_no' => 'required|digits:10',
            'contact_person_email' => 'required|email',
            'gst_no' => ['nullable', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'],
            'pan_no' => ['nullable', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
            'address_line_1' => 'required|string',
            'address_line_2' => 'nullable|string',
            'max_storage_capacity' => 'nullable|numeric|min:0',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'pincode' => 'required|digits:6',
            'status' => 'required|in:0,1',
            'supported_operations' => 'required|in:inbound,outbound,return',
        ];
    }
}
