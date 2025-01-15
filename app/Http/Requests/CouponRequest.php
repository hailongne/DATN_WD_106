<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
        $couponId = $this->route('id');
        return [
            'code' => 'required|string|unique:coupons,code,' . $couponId . ',coupon_id',
            'discount_amount' => 'nullable|numeric|min:1|required_without:discount_percentage',
            'discount_percentage' => 'nullable|numeric|min:1|max:99|required_without:discount_amount',
            'quantity' => 'required|integer|min:1',
            'min_order_value' => 'required|numeric|min:1',
            'max_order_value' => [
                'nullable',
                'min:1',
                function ($attribute, $value, $fail) {
                    // Kiểm tra nếu discount_percentage có giá trị mà max_order_value không có
                    if ($this->input('discount_percentage') !== null && $value === null) {
                        $fail('Giá trị tối đa của đơn hàng là bắt buộc khi có phần trăm giảm giá.');
                    }
                },
            ],
            'user_id' => 'nullable|exists:users,user_id',
            'is_public' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',

        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Yêu cầu không để trống mã coupon.',
            'code.unique' => 'Mã coupon đã tồn tại.',
            'discount_amount.numeric' => 'Giảm giá phải là một số.',
            'discount_amount.min' => 'Giảm giá phải lớn hơn 0.',
            'discount_amount.required_without' => 'Bạn phải nhập số tiền giảm giá nếu không nhập phần trăm giảm giá.',
            'discount_percentage.required_without' => 'Bạn phải nhập phần trăm giảm giá nếu không nhập số tiền giảm giá.',
            'discount_percentage.numeric' => 'Phần trăm giảm giá phải là một số.',
            'discount_percentage.min' => 'Phần trăm giảm giá phải lớn hơn 0.',
            'discount_percentage.max' => 'Phần trăm giảm giá không được vượt quá 100.',
            'quantity.required' => 'Yêu cầu không để trống số lượng coupon.',
            'quantity.integer' => 'Số lượng coupon phải là một số nguyên.',
            'quantity.min' => 'Số lượng coupon phải lớn hơn 0.',
            'min_order_value.numeric' => 'Giá trị đơn hàng tối thiểu phải là một số.',
            'min_order_value.min' => 'Giá trị đơn hàng tối thiểu phải lớn hơn hoặc bằng 1.',
            'min_order_value.required' => 'Giá trị đơn hàng tối thiểu không được để trống.',
            'max_order_value.min' => 'Giá trị đơn hàng tối đa phải lớn hơn hoặc bằng 1.',
            'condition.string' => 'Điều kiện phải là chuỗi ký tự.',
            'user_id.exists' => 'Người dùng không tồn tại.',
            'is_public.boolean' => 'Chỉ nhận giá trị true hoặc false.',
            'start_date.date' => 'Ngày bắt đầu phải có định dạng ngày hợp lệ.',
            'end_date.date' => 'Ngày kết thúc phải có định dạng ngày hợp lệ.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'is_active.boolean' => 'Chỉ nhận giá trị true hoặc false.',
        ];
    }



}
