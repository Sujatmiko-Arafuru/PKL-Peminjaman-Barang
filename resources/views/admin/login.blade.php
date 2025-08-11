<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin SarPras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/custom-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/components.css') }}" rel="stylesheet">
    <style>
        body { background: #E0FFFF; }
        .login-box { max-width: 400px; margin: 80px auto; background: #fff; border-radius: 1rem; box-shadow: 0 2px 16px rgba(32,178,170,0.10); padding: 2.5rem 2rem; }
        .text-primary { color: #20B2AA !important; }
        .btn-biru { background: #20B2AA; color: #fff; border-radius: 0.5rem; border: none; padding: 0.5rem 1.2rem; font-weight: 500; transition: background 0.2s; }
        .btn-biru:hover { background: #008B8B; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2 class="mb-4 text-center text-primary">Login Admin SarPras</h2>
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" name="email" class="form-control" required autofocus value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-biru">Login</button>
            </div>
        </form>
    </div>
</body>
</html> 