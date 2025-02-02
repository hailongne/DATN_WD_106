@extends('admin.index')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endpush

    <div class="button-header">
        <button>Thống kê <i class="fa fa-star"></i></button>
    </div>

    <div class="row g-2 mb-4">
        <div class="col-md-12">
            <form method="GET" action="{{ route('admin.dashboard') }}">
                <div class="row align-items-end">
                    <!-- Lọc theo ngày cho Doanh thu -->
                    <div class="col-md-2">
                        <label for="start_date" class="form-label">Từ ngày:</label>
                        <input type="date" id="start_date" name="start_date" class="form-control"
                            value="{{ request('start_date', $startDate) }}">
                    </div>
                    <div class="col-md-2">
                        <label for="end_date" class="form-label">Đến ngày:</label>
                        <input type="date" id="end_date" name="end_date" class="form-control"
                            value="{{ request('end_date', $endDate) }}">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="custom-btn-filte-dashboard w-100">Lọc</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Row chứa cả 2 biểu đồ (Doanh thu và Đơn hàng) song song -->
    <div class="row mb-4">
        <!-- Biểu đồ doanh thu theo ngày -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Doanh thu </div>
                <div class="card-body">
                    <canvas id="dailyRevenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Biểu đồ đơn hàng theo ngày -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Đơn hàng </div>
                <div class="card-body">
                    <canvas id="dailyOrdersChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">Thống kê số lượng sản phẩm đã bán</div>
            <div class="card-body">
                <canvas id="soldProductsChart"></canvas>
            </div>
        </div>
        </div>
    <div class="col-md-6">

            <!-- Biểu đồ sản phẩm theo danh mục -->
            <div class="card mb-4">
                <div class="card-header">Tổng sản phẩm theo danh mục</div>
            <div class="card-body">
                <canvas id="categoryChart"></canvas>
            </div>

            </div>        </div>
    </div>
    <div class="card mb-4">
    <div class="card-header">Thống kê sản phẩm trong kho</div>
    <div class="card-body">
        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Tìm kiếm sản phẩm...">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col" data-sort="product_id">Mã sản phẩm</th>
                    <th scope="col" data-sort="name">Tên sản phẩm</th>
                    <th scope="col" data-sort="size">Kích thước</th>
                    <th scope="col" data-sort="color">Màu sắc</th>
                    <th scope="col" data-sort="stock">Số lượng tồn kho</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                @foreach ($inventoryStats as $product)
                    @foreach ($product->attributeProducts as $attribute)
                        <tr>
                            <td data-column="product_id">{{ $product->product_id }}</td>
                            <td data-column="name">{{ $product->name }}</td>
                            <td data-column="size">{{ $attribute->size->name ?? 'Không xác định' }}</td>
                            <td data-column="color">{{ $attribute->color->name ?? 'Không xác định' }}</td>
                            <td data-column="stock">{{ $attribute->in_stock }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

    <div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Top 5 người dùng mua hàng nhiều nhất</h5>
    </div>
    <div class="card-body">
        @if($topUsers->isEmpty())
            <p class="text-muted">Không có dữ liệu.</p>
        @else
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Hạng</th>
                        <th>Tên người dùng</th>
                        <th>Số đơn hàng</th>
                        <th>Tổng chi tiêu (VNĐ)</th>
                        <th>Đơn hàng cao nhất (VNĐ)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topUsers as $index => $user)
                    <tr>
                        <td>#{{ $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->total_orders }}</td>
                        <td>{{ number_format($user->total_spent, 0, ',', '.') }}</td>
                        <td>{{ number_format($user->max_order_value, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>


<!-- Script vẽ biểu đồ -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const headers = document.querySelectorAll('th[data-sort]');
    
    headers.forEach(header => {
        header.addEventListener('click', function () {
            const column = header.getAttribute('data-sort');
            const rows = Array.from(document.querySelectorAll('#productTableBody tr'));
            const isAscending = header.classList.contains('asc');
            
            // Sắp xếp các dòng bảng theo cột được bấm
            rows.sort((a, b) => {
                const aText = a.querySelector(`td[data-column="${column}"]`).textContent.trim();
                const bText = b.querySelector(`td[data-column="${column}"]`).textContent.trim();

                // Kiểm tra nếu là cột số (Số lượng tồn kho)
                if (column === 'stock') {
                    return isAscending ? aText - bText : bText - aText;
                }
                // So sánh theo chữ (Tên sản phẩm, Màu sắc, Kích thước, Mã sản phẩm)
                return isAscending ? aText.localeCompare(bText) : bText.localeCompare(aText);
            });

            // Gắn lại các dòng đã sắp xếp vào bảng
            rows.forEach(row => document.querySelector('#productTableBody').appendChild(row));

            // Cập nhật các lớp CSS để thay đổi biểu tượng sắp xếp
            headers.forEach(h => h.classList.remove('asc', 'desc'));
            header.classList.add(isAscending ? 'desc' : 'asc');
        });
    });
    // Lọc theo ngày Doanh thu
    const startDateRevenue = document.getElementById('start_date');
    const endDateRevenue = document.getElementById('end_date');

    startDateRevenue.addEventListener('change', function() {
        if (endDateRevenue.value && new Date(startDateRevenue.value) > new Date(endDateRevenue.value)) {
            alert('Ngày kết thúc không được nhỏ hơn ngày bắt đầu ');
            endDateRevenue.value = ''; // Reset giá trị
        }
    });

    endDateRevenue.addEventListener('change', function() {
        if (startDateRevenue.value && new Date(startDateRevenue.value) > new Date(endDateRevenue.value)) {
            alert('Ngày kết thúc không được nhỏ hơn ngày bắt đầu ');
            endDateRevenue.value = ''; // Reset giá trị
        }
    });

    // Tìm kiếm sản phẩm
    const searchInput = document.getElementById('searchInput');
    const productTableBody = document.getElementById('productTableBody');

    searchInput.addEventListener('input', function() {
        const searchValue = searchInput.value.toLowerCase(); // Lấy giá trị tìm kiếm và chuyển thành chữ thường
        const rows = productTableBody.getElementsByTagName('tr'); // Lấy tất cả các dòng trong bảng

        Array.from(rows).forEach(row => {
            const cells = row.getElementsByTagName('td'); // Lấy tất cả các ô trong mỗi dòng
            let match = false;

            // Duyệt qua các ô trong mỗi dòng và kiểm tra xem có khớp với từ khóa tìm kiếm không
            Array.from(cells).forEach(cell => {
                if (cell.textContent.toLowerCase().includes(searchValue)) {
                    match = true;
                }
            });

            // Hiển thị hoặc ẩn dòng tùy thuộc vào việc có tìm thấy kết quả hay không
            row.style.display = match ? '' : 'none';
        });
    });
    
});


    // Biểu đồ doanh thu theo ngày
    const dailyRevenueCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    const dailyRevenueChart = new Chart(dailyRevenueCtx, {
        type: 'line',
        data: {
            labels: @json($dailyLabels),
            datasets: [{
                label: 'Doanh thu (VND)',
                data: @json($dailyRevenue),
                borderColor: 'rgb(35, 179, 179)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    // Biểu đồ sản phẩm theo danh mục
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryChart = new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: @json($categories -> pluck('name')), // Danh mục sản phẩm
            datasets: [{
                label: 'Số lượng sản phẩm',
                data: @json($categories -> pluck('products_count')), // Số lượng sản phẩm trong mỗi danh mục
                backgroundColor: 'rgba(54, 162, 235, 0.5)', // Màu nền của các cột
                borderColor: 'rgba(54, 162, 235, 1)', // Màu viền của các cột
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Biểu đồ đơn hàng theo ngày
    const dailyOrdersCtx = document.getElementById('dailyOrdersChart').getContext('2d');
    const dailyOrdersChart = new Chart(dailyOrdersCtx, {
        type: 'line',
        data: {
            labels: @json($ordersLabels),
            datasets: [{
                label: 'Số lượng đơn hàng',
                data: @json($ordersTotal),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
      // Lấy dữ liệu từ server
      const soldProductsLabels = @json($soldProductsStats->pluck('name'));
    const soldProductsData = @json($soldProductsStats->pluck('sold_quantity'));

    // Tạo biểu đồ cột
    const soldProductsCtx = document.getElementById('soldProductsChart').getContext('2d');
    const soldProductsChart = new Chart(soldProductsCtx, {
        type: 'bar',
        data: {
            labels: soldProductsLabels, // Tên sản phẩm
            datasets: [{
                label: 'Số lượng bán',
                data: soldProductsData, // Số lượng sản phẩm đã bán
                backgroundColor: 'rgba(54, 162, 235, 0.5)', // Màu nền các cột
                borderColor: 'rgba(54, 162, 235, 1)', // Màu viền các cột
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Số lượng bán'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tên sản phẩm'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>
@endsection
