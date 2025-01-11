<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $productId = $this->route('id'); // Lấy ID sản phẩm từ URL
        return [
            //   
            'brand_id' => 'required|integer|exists:brands,brand_id', // Kiểm tra brand_id phải tồn tại trong bảng brands
            'product_category_id' => 'required|integer|exists:categories,category_id', // Kiểm tra category_id phải tồn tại trong bảng categories
       'name' => 'required|string|max:255|unique:products,name,' . $productId . ',product_id', // Kiểm tra trùng lặp ngoại trừ sản phẩm hiện tại


       'main_image_url' => $this->isMethod('put') ? 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' : 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Chỉ bắt buộc khi tạo mới sản phẩm
            'sku' => 'required|string|max:255|unique:products,sku,' . $productId . ',product_id', // Mã sản phẩm là bắt buộc, không được trùng lặp
            'description' => 'required|string', // Mô tả sản phẩm là bắt buộc
            'subtitle' => 'required|string|max:255', // Phụ đề có thể rỗng nhưng không quá 255 ký tự
        ];
    }
    public function messages(): array
{
    return [
        'brand_id.required' => 'Yêu cầu chọn thương hiệu sản phẩm.',
        'brand_id.integer' => 'ID thương hiệu phải là một số nguyên.',
        'brand_id.exists' => 'Thương hiệu không tồn tại trong cơ sở dữ liệu.',
        
        'product_category_id.required' => 'Yêu cầu chọn danh mục sản phẩm.',
        'product_category_id.integer' => 'ID danh mục phải là một số nguyên.',
        'product_category_id.exists' => 'Danh mục không tồn tại trong cơ sở dữ liệu.',
        
        'name.required' => 'Tên sản phẩm là bắt buộc.',
        'name.string' => 'Tên sản phẩm phải là một chuỗi ký tự.',
        'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
        'name.unique' => 'Tên sản phẩm đã tồn tại.',  // Thông báo lỗi khi tên màu bị trùng
        
        'main_image_url.required' => 'Yêu cầu chọn ảnh chính cho sản phẩm.',
        'main_image_url.image' => 'Ảnh chính phải là một tệp hình ảnh.',
        'main_image_url.mimes' => 'Ảnh chính phải có định dạng jpeg, png, jpg, hoặc gif.',
        'main_image_url.max' => 'Ảnh chính không được vượt quá 2MB.',
        
        'sku.required' => 'Mã sản phẩm là bắt buộc.',
        'sku.string' => 'Mã sản phẩm phải là một chuỗi ký tự.',
        'sku.max' => 'Mã sản phẩm không được vượt quá 255 ký tự.',
        'sku.unique' => 'Mã sản phẩm đã tồn tại.',
        
        'description.required' => 'Mô tả sản phẩm là bắt buộc.',
        'description.string' => 'Mô tả sản phẩm phải là một chuỗi ký tự.',
        
        'subtitle.required' => 'Phụ đề là bắt buộc .',
        'subtitle.string' => 'Phụ đề phải là một chuỗi ký tự.',
        'subtitle.max' => 'Phụ đề không được vượt quá 255 ký tự.',
        
    ];
}

}
