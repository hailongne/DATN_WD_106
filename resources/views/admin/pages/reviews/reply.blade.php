<style>
/* CSS cải tiến */
.customer-comment {
    background-color: #e9f7ef;
    border: 1px solid #b2d8c7;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.customer-comment h5 {
    margin: 0 0 15px;
    color: #333;
    font-weight: bold;
    border-bottom: 2px solid #28a745;
    padding-bottom: 5px;
}

.customer-comment .info {
    font-size: 14px;
    color: #777;
    margin-bottom: 10px;
}

.customer-comment img {
    max-width: 100%;
    height: auto;
    border-radius: 5px;
    margin: 10px 0;
}

.form-group label {
    font-weight: bold;
    color: #555;
}

.custom-content span {
    font-size: 14px;
    font-weight: bold;
}

.custom-content p {
    margin-left: 10px;
    font-size: 16px;
}
</style>

<div class="container">
    <form action="{{route('admin.reviews.storeReply',$review->review_id)}}" method="POST">
        @csrf
        <div class="form-group">
            <input type="hidden" class="form-control" id="name" name="product_id" value="{{$product_id}}" />
        </div>
        <!-- Phần hiển thị Đánh giá của khách hàng -->
        <div class="customer-comment">
            <h5>Đánh giá của khách hàng</h5>
            <div class="info">
                <span><strong>Email:</strong> {{ $review->user->email }}</span><br>
                <span><strong>Thời gian:</strong>
                    {{ $review->created_at->format('d/m/Y H:i') }}
                </span><br>

                <span><strong>Đánh giá:</strong> {{ $review->rating }} ★</span><br>
                <span><strong>Sản phẩm:</strong> {{ $review->product->name }}</span><br>
            </div>
            <hr />
            <div class="custom-content">
                <span>Nội dung: </span>
                <p>{{ $review->comment }}</p>
            </div>
            <!-- <img src="{{ $review->product->main_image_url }}" alt="Ảnh sản phẩm"> -->
        </div>
        <!-- Phần nhập trả lời -->
        <div class="form-group">
            <input type="text" class="form-control" id="slug" name="content" placeholder="Nhập câu trả lời" />
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-primary">Gửi</button>
        </div>
    </form>
</div>
