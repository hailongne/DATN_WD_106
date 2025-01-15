<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gentle Manor')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('imagePro/image/logo/logo-remove2.png') }}" type="image/png">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />

    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@300;400&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slide.css') }}">
    <link rel="stylesheet" href="{{ asset('css/productList.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styleWeb.css') }}">
    <link rel="stylesheet" href="{{ asset('css/detailProduct.css') }}">
    @stack('styles')

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
            <!-- Nút liên hệ -->
    <button id="contactBtn" class="btn btn-primary" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
        Liên Hệ
    </button>
    <!-- Phần Liên hệ -->
<div class="contact-section" id="contactSection" style="display: none; position: fixed; bottom: 0; left: 50%; transform: translateX(-50%); width: 100%; background-color: #fff; padding: 20px; border-radius: 8px 8px 0 0; box-shadow: 0px -2px 10px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease, opacity 0.3s ease; opacity: 0; visibility: hidden;">
    <h2>Liên Hệ Với Chúng Tôi</h2>
    <form action="{{ route('contact.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Họ và tên</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="phone">Số điện thoại</label>
            <input type="text" id="phone" name="phone" class="form-control">
        </div>
        <div class="form-group">
            <label for="message">Lời nhắn</label>
            <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Gửi Liên Hệ</button>
    </form>
    <button id="closeContactForm" class="btn btn-secondary" style="position: absolute; top: 10px; right: 10px;">Đóng</button>
</div>

    </main>

    <section>
        @include('user.components.footer')
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    @stack('scripts')
</body>
<script>
    window.onload = function() {
        // Đảm bảo alert ẩn sau khi load
        var alertElement = document.getElementById('alert');
        if (alertElement) {
            setTimeout(function() {
                alertElement.style.display = 'none';
            }, 2000); // 2000ms = 2 seconds
        }
        
        // Hiển thị form liên hệ khi nhấn nút "Liên Hệ"
        var contactBtn = document.getElementById('contactBtn');
        var contactSection = document.getElementById('contactSection');
        
        if (contactBtn && contactSection) {
            contactBtn.addEventListener('click', function() {
                contactSection.style.display = 'block'; // Hiện phần liên hệ
                contactSection.style.opacity = '1'; // Đảm bảo opacity là 1 để hiện form
                contactSection.style.visibility = 'visible';
                contactSection.style.transform = 'translateX(-50%) translateY(0)';
            });
        }

        // Đóng form khi nhấn nút "Đóng"
        var closeBtn = document.getElementById('closeContactForm');
        if (closeBtn && contactSection) {
            closeBtn.addEventListener('click', function() {
                contactSection.style.display = 'none'; // Ẩn phần liên hệ
                contactSection.style.opacity = '0'; // Đảm bảo opacity là 0
                contactSection.style.visibility = 'hidden';
                contactSection.style.transform = 'translateX(-50%) translateY(100%)'; // Đẩy form xuống dưới
            });
        }
    }
</script>




</html>
