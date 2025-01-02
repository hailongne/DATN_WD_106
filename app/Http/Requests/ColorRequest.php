<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColorRequest extends FormRequest
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
        $ccolorId = $this->route('id');
        return [
            'name' => 'required|string|max:50|unique:colors,name,'.$ccolorId.',color_id' , // Kiểm tra duy nhất cho name trong bảng colors
           'color_code' => 'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/|unique:colors,color_code',

        ];
    }
    
    public function messages(): array
    {
     
        return [
            'name.required' => 'Yêu cầu không để trống',
            'name.unique' => 'Tên màu đã tồn tại.',  // Thông báo lỗi khi tên màu bị trùng
            'name.max' => 'Tên màu không được vượt quá 50 ký tự.',
            'name.string' => 'Tên màu phải là chuỗi ký tự.',
            'color_code.required' => 'Yêu cầu không để trống',
            'color_code.string' => 'Mã màu phải là chuỗi ký tự.',
            'color_code.size' => 'Mã màu phải có độ dài chính xác 7 ký tự.',
            'color_code.regex' => 'Mã màu phải có định dạng #RRGGBB, ví dụ: #FF5733',  // Kiểm tra định dạng mã màu hex
        ];
    }
    
}
