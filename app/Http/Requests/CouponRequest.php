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
            'discount_amount' => [
                'nullable',
                'numeric',
                'min:1',
                'required_without:discount_percentage',
                function ($attribute, $value, $fail) {
                    $minOrderValue = $this->input('min_order_value');
                    $maxOrderValue = $this->input('max_order_value');
                    if ($value !== null && ($value < $minOrderValue || ($maxOrderValue !== null && $value > $maxOrderValue))) {
                        $fail('Giá trị giảm giá phải nằm giữa giá trị tối thiểu và tối đa của đơn hàng.');
                    }
                },
            ],
            'discount_percentage' => [
                'nullable',
                'numeric',
                'min:1',
                'max:99',
                'required_without:discount_amount',
                function ($attribute, $value, $fail) {
                    if ($value !== null && $this->input('max_order_value') === null) {
                        $fail('Phần trăm giảm giá yêu cầu phải có giá trị tối đa của đơn hàng.');
                    }
                },
            ],
            'quantity' => 'required|integer|min:1',
            'min_order_value' => 'required|numeric|min:1',
            'max_order_value' => [
                'required',
                'nullable',
                'numeric',
                'min:1',
                'max:99999999',
                function ($attribute, $value, $fail) {
                    if ($this->input('discount_percentage') !== null && $value === null) {
                        $fail('Giá trị tối đa của đơn hàng là bắt buộc khi có phần trăm giảm giá.');
                    }
                },
            ],
            'user_id' => 'nullable|exists:users,user_id',
            'is_public' => 'boolean',
<<<<<<< HEAD
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
=======
            'start_date' => [
                'required',  // Bắt buộc điền
                'date',  // Phải là định dạng ngày tháng hợp lệ
                function ($attribute, $value, $fail) {
                    if ($value !== null && strtotime($value) > time()) {
                        $fail('Ngày bắt đầu không được ở tương lai.');
                    }
                },
            ],
            'end_date' => [
                'required',  // Bắt buộc điền
                'date',  // Phải là định dạng ngày tháng hợp lệ
                'after:start_date',  // Ngày kết thúc phải sau ngày bắt đầu
                function ($attribute, $value, $fail) {
                    if ($value !== null && strtotime($value) < time()) {
                        $fail('Ngày kết thúc không được ở trong quá khứ.');
                    }
                },
            ],
>>>>>>> 4b289bba82cb21842d6b4a27f0f006f9c7bc13b9

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
            'discount_percentage.max' => 'Phần trăm giảm giá không được vượt quá 99%.',
            'quantity.required' => 'Yêu cầu không để trống số lượng coupon.',
            'quantity.integer' => 'Số lượng coupon phải là một số nguyên.',
            'quantity.min' => 'Số lượng coupon phải lớn hơn 0.',
            'min_order_value.numeric' => 'Giá trị đơn hàng tối thiểu phải là một số.',
            'min_order_value.min' => 'Giá trị đơn hàng tối thiểu phải lớn hơn hoặc bằng 1.',
            'min_order_value.required' => 'Giá trị đơn hàng tối thiểu không được để trống.',
            'max_order_value.required' => 'Giá trị đơn hàng tối thiểu không được để trống.',
            'max_order_value.min' => 'Giá trị đơn hàng tối đa phải lớn hơn hoặc bằng 1.',
            'max_order_value.max' => 'Giá trị đơn hàng tối đa phải nhỏ hơn 99.999.999đ.',
            'condition.string' => 'Điều kiện phải là chuỗi ký tự.',
            'user_id.exists' => 'Người dùng không tồn tại.',
            'is_public.boolean' => 'Chỉ nhận giá trị true hoặc false.',
            'start_date.date' => 'Ngày bắt đầu phải có định dạng ngày hợp lệ.',
            'start_date.after_or_equal' => 'Ngày bắt đầu không được nhỏ hơn ngày hiện tại.',
            'end_date.date' => 'Ngày kết thúc phải có định dạng ngày hợp lệ.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
            'end_date.required' => 'Chọn ngày kết thúc ưu đãi.',
            'start_date.required' => 'Chọn ngày bắt đầu ưu đãi.',
            'is_active.boolean' => 'Chỉ nhận giá trị true hoặc false.',
        ];
    }

}
