<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:categories,name',  // Kiểm tra duy nhất cho name
            'description' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',  // Kiểm tra hình ảnh đúng định dạng và kích thước
            'is_active' => 'boolean',
            'slug' => [
                'required',
                'alpha_dash',
                'min:3',
                'unique:categories,slug',  // Slug must be unique
                function ($attribute, $value, $fail) {
                    // Remove spaces from both name and slug to compare them in the same format
                    $slugWithoutSpace = str_replace(' ', '-', $this->input('name'));  // Replace spaces with dashes
                    if ($value !== $slugWithoutSpace) {
                        $fail('Slug phải giống với Name, và phải thay thế các dấu cách bằng dấu gạch ngang.');
                    }
                }
            ],
            'parent_id' => 'nullable',  // Kiểm tra nếu có thì phải tồn tại trong bảng categories
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'Yêu cầu không để trống',
            'name.unique' => 'Tên thương hiệu đã tồn tại.',  // Thông báo lỗi khi tên bị trùng
            'description.required' => 'Yêu cầu không để trống',
            'image.required' => 'Yêu cầu không để trống',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Chỉ chấp nhận định dạng jpeg, png, jpg, gif.',
            'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'is_active.boolean' => 'Chỉ nhận giá trị true hoặc false hay 0 hoặc 1',
            'slug.required' => 'Yêu cầu không để trống',
            'slug.alpha_dash' => 'Slug chỉ cho phép ký tự chữ cái, số, dấu gạch ngang và dấu gạch dưới',
            'slug.unique' => 'Slug đã tồn tại.',
            'slug.min' => 'Slug phải có ít nhất 3 ký tự',
        ];
    }
    
}
