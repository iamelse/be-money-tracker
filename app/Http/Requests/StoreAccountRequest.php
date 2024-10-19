<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
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
            'user_id' => 'exists:users,id',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|unique:accounts,account_number',
            'brand' => 'required|string|max:255',
            'account_type' => 'required|string|in:checking,savings,credit',
            'balance' => 'nullable|integer|min:0',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('balance')) {
            $this->merge([
                'balance' => (int) str_replace(['Rp ', '.', ','], ['', '', ''], $this->balance),
            ]);
        }
    }
}
