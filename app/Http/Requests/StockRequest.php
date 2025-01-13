<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
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
            'in_stock' => 'required|numeric|integer|min:6',
        ];
    }
    public function messages(): array
    {
        return [
            'in_stock.required' => 'Vui lòng nhập số lượng tồn kho',
            'in_stock.numeric'  => 'Số lượng tồn kho phải là số',
            'in_stock.integer'  => 'Số lượng tồn kho phải là số nguyên',
            'in_stock.min'      => 'Số lượng tồn kho phải lớn hơn hoặc bằng 6',
        ];
    }
}
