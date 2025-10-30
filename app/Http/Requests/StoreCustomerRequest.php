<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreCustomerRequest extends FormRequest
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
        // Default rule for email: required|email|unique:customers
        $emailRules = ['required', 'email', 'max:255', Rule::unique('customers', 'email')];

        // If the request is for updating an existing customer (i.e., we have a customer ID in the route)
        if ($this->route('customer')) {
            // Modify the unique rule to ignore the current customer's email address
            $customerId = $this->route('customer');
            $emailRules = [
                'required', 
                'email', 
                'max:255', 
                Rule::unique('customers')->ignore($customerId, 'id')
            ];
        }

        return [
            'name' => 'required|string|max:255',
            'email' => $emailRules,
            'phone' => 'nullable|string|max:20', // Optional field
        ];
    }

    /**
     * Custom error messages for specific fields
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The customer name is required.',
            'email.unique' => 'This email address is already registered to another customer.',
        ];
    }
}
