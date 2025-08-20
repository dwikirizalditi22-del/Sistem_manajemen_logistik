{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PT. Bumi Berkah Boga</title>

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #7E1014, #DBDBDB);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(6px);
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0px 6px 20px rgba(0,0,0,0.15);
            animation: fadeIn 0.8s ease-in-out;
        }
        .logo-img {
            width: 120px;
            margin-bottom: 1rem;
        }
        .form-control {
            border-radius: 0.5rem;
            padding-left: 2.5rem;
        }
        .input-icon {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #7E1014;
        }
        .btn-login {
            background: linear-gradient(135deg, #7E1014, #DBDBDB);
            color: white;
            font-weight: bold;
            border-radius: 0.5rem;
            transition: 0.3s;
        }
        .btn-login:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="login-card text-center">
    <img src="{{ asset('assets/simbol-kopi-kenangan.png') }}" alt="Logo" class="logo-img">

    @if ($errors->any())
        <div class="alert alert-danger text-start">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3 position-relative">
            <i class="fas fa-user input-icon"></i>
            <input type="text" class="form-control" name="nama" id="nama" 
                   placeholder="Username"
                   value="{{ old('nama') }}" required autofocus>
        </div>

        <div class="mb-3 position-relative">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" class="form-control" name="password" id="password" 
                   placeholder="Password"
                   required>
        </div>

        <button type="submit" class="btn btn-login w-100 mt-2">
            <i class="fas fa-sign-in-alt me-2"></i> Login
        </button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
