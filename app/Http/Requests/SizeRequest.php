<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SizeRequest extends FormRequest
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
        $sizeId = $this->route('id');
        return [
            'name' => 'required|string|max:50|unique:sizes,name,'.$sizeId.',size_id',  // Kiểm tra duy nhất cho name trong bảng sizes
        ];
    }
    
    public function messages(): array
    {
        return [
            'name.required' => 'Yêu cầu không để trống',
            'name.unique' => 'Tên kích thước đã tồn tại.',  // Thông báo lỗi khi tên bị trùng
            'name.max' => 'Tên kích thước không được vượt quá 50 ký tự.',
            'name.string' => 'Tên kích thước phải là chuỗi ký tự.',
        ];
    }
    
}
