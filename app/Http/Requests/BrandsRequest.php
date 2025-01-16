<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
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
        $brandId = $this->route('id'); // Lấy ID sản phẩm từ URL
        return [
            'name' => 'required|string|max:255|unique:brands,name,'. $brandId . ',brand_id',  // Kiểm tra duy nhất cho name
            'description' => 'required|string|max:255|nullable',
            'is_active' => 'boolean',
         'slug' => [
    'required',
    'unique:brands,slug,' . $brandId . ',brand_id',
    'alpha_dash',
    'min:3',
    'unique:brands,slug',
    function ($attribute, $value, $fail) {
        // Chuẩn hóa slug và name để so sánh
        $name = $this->input('name');
        $normalizedSlug = $this->generateSlug($name);
        
        if (strtolower($value) !== strtolower($normalizedSlug)) {
            $fail('Slug phải giống với Name (sau khi thay thế các dấu cách bằng dấu gạch ngang và loại bỏ dấu).');
        }
    },
],

        ];
        
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'Yêu cầu không để trống',
            'name.max' => 'Tên thưogn hiêuk không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên thương hiệu đã tồn tại.',  // Thông báo lỗi khi tên bị trùng
            'slug.unique' => 'Slug đã tồn tại. Vui lòng chọn một slug khác.',
            'description.required' => 'Yêu cầu không để trống',
            'is_active.boolean' => 'Chỉ nhận giá trị true hoặc false hay 0 hoặc 1',
            'slug.required' => 'Slug là bắt buộc',
            
            'slug.alpha_dash' => 'Slug chỉ cho phép ký tự chữ cái, số, dấu gạch ngang và dấu gạch dưới',
            'slug.min' => 'Slug phải có ít nhất 3 ký tự',
        ];
    }
    protected function generateSlug($string)
    {
        // Loại bỏ dấu (nếu bạn dùng Laravel thì nên dùng thư viện `Str::slug` thay vì xử lý thủ công)
        return Str::slug($string);
    }
    
}
