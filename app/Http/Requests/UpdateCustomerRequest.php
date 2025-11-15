<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
        $customerId = $this->route('customer');
        
        return [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255|unique:customers,code,' . $customerId,
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customerId,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'opening_balance' => 'nullable|numeric',
            'is_active' => 'nullable|boolean',
        ];
    }
}
