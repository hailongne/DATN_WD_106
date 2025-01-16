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

    <!-- Thêm SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.22/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Thêm SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.22/dist/sweetalert2.all.min.js"></script>


    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slide.css') }}">
    <link rel="stylesheet" href="{{ asset('css/productList.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styleWeb.css') }}">
    <link rel="stylesheet" href="{{ asset('css/detailProduct.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


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

    <main class="container-contacts">
        @yield('content')
        <!-- Nút liên hệ -->
        <img src="{{ asset('imagePro/icon/icon-chat.png') }}" id="contactBtn"
            class="icon-chat-contact"/>

        <!-- Phần Liên hệ -->
        <div class="contact-section" id="contactSection">
            <div class="button-header-contacts">
                <button>
                    Liên Hệ Với Chúng Tôi <i class="fa fa-star"></i>
                </button>
            </div>
            <form action="{{ route('contact.store') }}" method="POST" class="form-contacts-sent">
                @csrf
                <div class="form-group">
                    <label for="message" class="label-contacts">Họ và tên</label>
                    <input type="text" id="name" name="name" placeholder="Nhập họ và tên" class="form-control-contacts" required>
                </div>
                <div class="form-group">
                    <label for="message" class="label-contacts">Email</label>
                    <input type="email" id="email" name="email" placeholder="Nhập email" class="form-control-contacts" required>
                </div>
                <div class="form-group">
                    <label for="message" class="label-contacts">Số điện thoại</label>
                    <input type="text" id="phone" name="phone" placeholder="Nhập số điện thoại" class="form-control-contacts">
                </div>
                <div class="form-group">
                    <label for="message" class="label-contacts">Lời nhắn</label>
                    <textarea id="message" name="message" class="form-control-contacts" placeholder="Vấn đề của bạn........." required></textarea>
                </div>
                <button type="submit" class="btn btn-contacts">Gửi <i class="fas fa-paper-plane"></i></button>
            </form>
            <button id="closeContactForm" class="btn btn-secondary"
                style="position: absolute; top: 10px; right: 10px;"><i class="fas fa-times"></i></button>
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
