<?php

namespace App\Http\Requests;
use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $categoryId = $this->route('id');
        return [
            'name' => 'required|string|max:255|unique:categories,name,'.$categoryId.',category_id',  // Kiểm tra duy nhất cho name
            'description' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',  // Kiểm tra hình ảnh đúng định dạng và kích thước
            'is_active' => 'boolean',
            'slug' => [
                'required',
                'unique:categories,slug,' . $categoryId . ',category_id',
                'alpha_dash',
                'min:3',
                function ($attribute, $value, $fail) {
                    // Chuẩn hóa slug và name để so sánh
                    $name = $this->input('name');
                    $normalizedSlug = $this->generateSlug($name);
                    
                    if (strtolower($value) !== strtolower($normalizedSlug)) {
                        $fail('Slug phải giống với Name (sau khi thay thế các dấu cách bằng dấu gạch ngang và loại bỏ dấu).');

                    }
                },
            ],
            'parent_id' => 'nullable',  // Kiểm tra nếu có thì phải tồn tại trong bảng categories
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Yêu cầu không để trống',
            'name.unique' => 'Tên thương hiệu đã tồn tại.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
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
    protected function generateSlug($string)
    {
        // Loại bỏ dấu (nếu bạn dùng Laravel thì nên dùng thư viện `Str::slug` thay vì xử lý thủ công)
        return Str::slug($string);
    }
}


