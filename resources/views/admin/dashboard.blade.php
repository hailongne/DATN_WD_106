@extends('admin.index')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush
<div class="container">
    <div class="button-header">
        <button>
            Thống kê <i class="fa fa-star"></i>
        </button>
    </div>

    <!-- Row chứa form lọc song song -->
    <div class="row g-2 mb-4">
        <!-- Form lọc doanh thu -->
        <div class="col-md-6">
            <form method="GET" action="{{ route('admin.dashboard') }}">
                <div class="row align-items-end">
                    <div class="col-md-6">
                        <label for="start_date_revenue" class="form-label">Từ ngày (Doanh thu):</label>
                        <input type="date" id="start_date_revenue" name="start_date_revenue" class="form-control" value="{{ request('start_date_revenue', $startDateRevenue) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="end_date_revenue" class="form-label">Đến ngày (Doanh thu):</label>
                        <input type="date" id="end_date_revenue" name="end_date_revenue" class="form-control" value="{{ request('end_date_revenue', $endDateRevenue) }}">
                    </div>
                    <div class="col-md-12 mt-3">
                        <button type="submit" class="custom-btn-filte-dashboard w-100">Lọc (Doanh thu)</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Form lọc đơn hàng -->
        <div class="col-md-6">
            <form method="GET" action="{{ route('admin.dashboard') }}">
                <div class="row align-items-end">
                    <div class="col-md-6">
                        <label for="start_date_orders" class="form-label">Từ ngày (Đơn hàng):</label>
                        <input type="date" id="start_date_orders" name="start_date_orders" class="form-control" value="{{ request('start_date_orders', $startDateOrders) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="end_date_orders" class="form-label">Đến ngày (Đơn hàng):</label>
                        <input type="date" id="end_date_orders" name="end_date_orders" class="form-control" value="{{ request('end_date_orders', $endDateOrders) }}">
                    </div>
                    <div class="col-md-12 mt-3">
                        <button type="submit" class="custom-btn-filte-dashboard w-100">Lọc (Đơn hàng)</button>
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
    <div class="card mb-4">
        <div class="card-header">Thống kê số lượng sản phẩm đã bán</div>
        <div class="card-body">
            <canvas id="soldProductsChart"></canvas>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">Thống kê sản phẩm trong kho</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Mã sản phẩm</th>
                        <th scope="col">Tên sản phẩm</th>
                        <th scope="col">Kích thước</th>
                        <th scope="col">Màu sắc</th>
                        <th scope="col">Số lượng tồn kho</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inventoryStats as $product)
                    @foreach ($product->attributeProducts as $attribute)
                    <tr>
                        <td>{{ $product->product_id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $attribute->size->name ?? 'Không xác định' }}</td>
                        <td>{{ $attribute->color->name ?? 'Không xác định' }}</td>
                        <td>{{ $attribute->in_stock }}</td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div


        <!-- Biểu đồ sản phẩm theo danh mục -->
    <div class="card mb-4">
        <div class="card-header">Tổng sản phẩm theo danh mục</div>
        <div class="card-body">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<!-- Script vẽ biểu đồ -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Doanh thu
        const startDateRevenue = document.getElementById('start_date_revenue');
        const endDateRevenue = document.getElementById('end_date_revenue');

        startDateRevenue.addEventListener('change', function() {
            if (endDateRevenue.value && new Date(startDateRevenue.value) > new Date(endDateRevenue.value)) {
                alert('Ngày bắt đầu không được lớn hơn ngày kết thúc (Doanh thu).');
                startDateRevenue.value = ''; // Reset giá trị
            }
        });

        endDateRevenue.addEventListener('change', function() {
            if (startDateRevenue.value && new Date(startDateRevenue.value) > new Date(endDateRevenue.value)) {
                alert('Ngày kết thúc không được nhỏ hơn ngày bắt đầu (Doanh thu).');
                endDateRevenue.value = ''; // Reset giá trị
            }
        });

        // Đơn hàng
        const startDateOrders = document.getElementById('start_date_orders');
        const endDateOrders = document.getElementById('end_date_orders');

        startDateOrders.addEventListener('change', function() {
            if (endDateOrders.value && new Date(startDateOrders.value) > new Date(endDateOrders.value)) {
                alert('Ngày bắt đầu không được lớn hơn ngày kết thúc (Đơn hàng).');
                startDateOrders.value = ''; // Reset giá trị
            }
        });

        endDateOrders.addEventListener('change', function() {
            if (startDateOrders.value && new Date(startDateOrders.value) > new Date(endDateOrders.value)) {
                alert('Ngày kết thúc không được nhỏ hơn ngày bắt đầu (Đơn hàng).');
                endDateOrders.value = ''; // Reset giá trị
            }
        });
    });
</script>
@endsection