<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('imagePro/image/logo/logoremove-white.png') }}" type="image/png">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script><!-- Thêm TinyMCE từ CDN -->
    <!-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> -->



    <link rel="stylesheet" href="{{asset('css/admin/headerAdmin.css')}}">
    <link rel="stylesheet" href="{{asset('css/admin/admin.css')}}">
    <link rel="stylesheet" href="{{asset('css/admin/table.css')}}">
    <link rel="stylesheet" href="{{asset('css/admin/form.css')}}">
    @stack('styles')
    <style>
    *:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    /* Đặt chung */
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        flex-direction: column;
    }

    header {
        width: 100%;
        z-index: 1000;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Header top */
    .header-top {
        position: sticky;
        top: 0;
        height: 50px;
    }

    /* Header main */
    .header-main {
        position: sticky;
        top: 50px;
        height: 60px;
    }

    /* Main content */
    main {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        margin-left: 220px;
        padding-right: 50px;
        padding-left: 50px;
    }
    </style>
</head>

<body>
    <header>
        @include('admin.layoutAdmin.header-top-admin')
        <!-- Include file header -->
    </header>
    <header>
        @include('admin.layoutAdmin.header-admin')
        <!-- Include file header -->
    </header>
    <main>
        @yield('content')
    </main>
    @stack('scripts')
</body>

<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Kích hoạt tất cả tooltips
  document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
      new bootstrap.Tooltip(tooltipTriggerEl)
    });
  });
</script>

</html>
