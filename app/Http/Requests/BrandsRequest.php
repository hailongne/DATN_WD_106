<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandsRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:brands,name',  // Kiểm tra duy nhất cho name
            'description' => 'required|string|max:255|nullable',
            'is_active' => 'boolean',
            'slug' => [
                'required',
                'alpha_dash',
                'min:3',
                'unique:brands,slug',  // Slug must be unique
                function ($attribute, $value, $fail) {
                    // Remove spaces from both name and slug to compare them in the same format
                    $slugWithoutSpace = str_replace(' ', '-', $this->input('name'));  // Replace spaces with dashes
                    if ($value !== $slugWithoutSpace) {
                        $fail('Slug phải giống với Name, và phải thay thế các dấu cách bằng dấu gạch ngang.');
                    }
                }
            ],
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'Yêu cầu không để trống',
            'name.unique' => 'Tên thương hiệu đã tồn tại.',  // Thông báo lỗi khi tên bị trùng
            'description.required' => 'Yêu cầu không để trống',
            'is_active.boolean' => 'Chỉ nhận giá trị true hoặc false hay 0 hoặc 1',
            'slug.required' => 'Slug là bắt buộc',
            'slug.alpha_dash' => 'Slug chỉ cho phép ký tự chữ cái, số, dấu gạch ngang và dấu gạch dưới',
            'slug.min' => 'Slug phải có ít nhất 3 ký tự',
        ];
    }
    
}
