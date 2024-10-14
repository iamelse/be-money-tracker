<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'user_id' => 'sometimes|exists.users,id',
            'account_id' => 'required|exists:accounts,id',
            'amount' => 'required|integer',
            'transaction_date' => 'required|date',
            'transaction_type' => 'sometimes',
            'category' => 'required|string',
            'description' => 'required|string',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('amount')) {
            $this->merge([
                'amount' => (int) str_replace(['Rp ', '.', ','], ['', '', ''], $this->amount),
            ]);
        }
    }
}