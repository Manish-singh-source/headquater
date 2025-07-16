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
            //
            'name' => 'required|min:3',
            'type' => 'required',
            'contact_person_name' => 'required|min:3',
            'contact_person_phone_no' => 'required|digits:10',
            'contact_person_alt_phone_no' => 'required|digits:10',
            'contact_person_email' => 'required',
            'address_line_1' => 'required',
        ];
    }
}
