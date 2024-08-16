<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
                'user_id' => 'required|exists:users,id',
                'booking_id' => 'required|exists:bookings,id',
                'payment_method' => 'required|string',
                'amount' => 'required|numeric',
                'transaction_id' => 'required|string|unique:payments,transaction_id',
                'currency' => 'required|string',
                'status' => 'required|string',
            ];
        
    }
}
