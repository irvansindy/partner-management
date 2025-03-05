<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Management</title>
    
    <!-- Bootstrap & Custom Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('fonts/material-icon/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css_auth/style.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    <style>
        .auth-container {
            max-width: 400px;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .btn-custom {
            width: 100%;
            margin-top: 12px;
            font-weight: bold;
            border-radius: 8px;
        }
        .btn-outline-primary:hover {
            background-color: #007bff;
            color: #fff;
        }
        .logo-img {
            width: 160px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="bg-gradient d-flex justify-content-center align-items-center vh-100" style="background: linear-gradient(135deg, #74ebd5, #acb6e5);">
    <div class="auth-container">
        <img src="{{ asset('uploads/logo/logo.png') }}" alt="Logo" class="logo-img">
        <h4 class="fw-bold text-primary">Selamat Datang</h4>
        <p class="text-muted">Kelola Partner Anda dengan Mudah</p>
        
        <a href="{{ route('register') }}" class="btn btn-primary btn-custom">Daftar</a>
        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-custom">Masuk</a>
    </div>
    
    <!-- Bootstrap Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
