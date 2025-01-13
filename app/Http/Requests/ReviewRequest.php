<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'product_id' => 'required|exists:products,product_id',
            'user_id'    => 'required|exists:users,user_id',
            'image'      => 'required|file|mimes:jpeg,png,jpg,gif|max:2048', // Bắt buộc, phải là file PNG, kích thước tối đa 2MB
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'required|string|min:10|max:1000',
            'is_active'  => 'nullable|boolean',
        ];
    }
    public function messages(): array
    {
        return [
            'product_id.required' => 'Sản phẩm không được để trống.',
            'product_id.exists'   => 'Sản phẩm không tồn tại.',
            'user_id.required'    => 'Người dùng không được để trống.',
            'user_id.exists'      => 'Người dùng không tồn tại.',
            'image.required'      => 'Ảnh không được để trống.',
            'image.file'          => 'Ảnh phải là một file hợp lệ.',
            'image.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, hoặc gif.',
            'image.max'           => 'Kích thước ảnh không được vượt quá 2MB.',
            'rating.required'     => 'Đánh giá không được để trống.',
            'rating.integer'      => 'Đánh giá phải là một số nguyên.',
            'rating.min'          => 'Đánh giá tối thiểu là 1 sao.',
            'rating.max'          => 'Đánh giá tối đa là 5 sao.',
            'comment.required'    => 'Bình luận không được để trống.',
            'comment.string'      => 'Bình luận phải là một chuỗi ký tự.',
            'comment.min'         => 'Bình luận phải dài ít nhất 10 ký tự.',
            'comment.max'         => 'Bình luận không được dài quá 1000 ký tự.',
            'is_active.boolean'   => 'Trạng thái phải là true hoặc false.',
        ];
    }
}
