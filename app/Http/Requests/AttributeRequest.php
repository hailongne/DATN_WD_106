<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttributeRequest extends FormRequest
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
            'price' => 'required|array',  // Kiểm tra price là mảng
            'price.*' => 'required|numeric|min:1',  // Kiểm tra từng phần tử của mảng price
            
            'in_stock' => 'required|array',  // Kiểm tra in_stock là mảng
            'in_stock.*' => 'required|integer|min:1',  // Kiểm tra từng phần tử của mảng in_stock
        ];
    }
    public function messages(): array
{
    return [
        'price.required' => 'Vui lòng nhập giá sản phẩm.',
        'price.array' => 'Giá sản phẩm phải là một mảng.',
        'price.*.required' => 'Vui lòng nhập giá cho từng sản phẩm.',
        'price.*.numeric' => 'Giá của mỗi sản phẩm phải là một số.',
        'price.*.min' => 'Giá của mỗi sản phẩm phải lớn hơn hoặc bằng 1.',

        'in_stock.required' => 'Vui lòng nhập số lượng sản phẩm.',
        'in_stock.array' => 'Số lượng sản phẩm phải là một mảng.',
        'in_stock.*.required' => 'Vui lòng nhập số lượng cho từng sản phẩm.',
        'in_stock.*.integer' => 'Số lượng của mỗi sản phẩm phải là một số nguyên.',
        'in_stock.*.min' => 'Số lượng của mỗi sản phẩm phải lớn hơn hoặc bằng 1.',
    ];
}

    

}
