<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gentle Manor')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('imagePro/image/logo/logoremove-white.png') }}" type="image/png">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slide.css') }}">

    @stack('styles')
    <style>
    *:focus {
        border: 1px solid black;
        outline: none !important;
        box-shadow: none !important;
    }

    button:focus,
    input:focus,
    textarea:focus,
    a:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    body {
        font-family: 'Roboto', sans-serif;
        font-smooth: always;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    html,
    body {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
        font-size: 16px;
        line-height: 1.6;
    }

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-weight: 700;
    }

    p {
        font-weight: 400;
    }

    /* Tổng quan cho alert */
    #alert {
        position: fixed;
        /* Cố định ở một vị trí */
        top: 20px;
        right: 20px;
        /* Đẩy về bên phải */
        max-width: 300px;
        /* Giới hạn chiều rộng */
        padding: 15px;
        border-radius: 5px;
        color: #fff;
        /* Màu chữ trắng */
        font-size: 14px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        animation: slideIn 0.5s ease-out;
        z-index: 1000;
        /* Đảm bảo hiển thị trên các phần khác */
    }

    /* Hiệu ứng trượt vào từ bên phải */
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Alert thành công màu xanh lá */
    .alert-success {
        background-color: #28a745;
        border: 1px solid #218838;
    }

    .alert-fail {
        background-color: #333;
        border: 1px solid #222;
    }

    .alert {
        position: relative;
        z-index: 1200;
        margin-top: 10px;
    }
    </style>

</head>

<body>
    <header>
        @include('user.components.header')
    </header>

    @if(!empty(session('alert')))
    <div class="alert alert-success" id="alert" role="alert">
        {{ session('alert') }}
        @if(!empty(session('alert_2')))
        <br>
        {{ session('alert_2') }}
        @endif
    </div>
    @endif

    @if(Request::is('/'))
    <div class="slide-show">
        @include('user.components.slide-show')
    </div>
    @endif

    <main class="container">
        @yield('content')
    </main>

    <section>
        @include('user.components.footer')
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
<script>
window.onload = function() {
    var alertElement = document.getElementById('alert', 'alert_2');
    if (alertElement) {
        // Sau 2 giây (2000ms), ẩn đi thông báo alert
        setTimeout(function() {
            alertElement.style.display = 'none';
        }, 2000); // 2000 milliseconds = 2 seconds
    }
}
</script>

</html>