<head>
    <title>Klasifikasi C4.5 | Login</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
    <script defer src="{{ asset('bootstrap/bootstrap.min.js') }}"></script>

    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .backround-image {
            position: relative;
            height: 100vh;
            background-image: url('{{ asset('img/bg.png') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-attachment: fixed;
        }
        .login-box {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .login-box h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="backround-image">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4 login-box">
                    <h2 class="text-center">Masuk</h2>

                    @include('pages.partials.session-notification')

                    <form action="{{ route('authenticate') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Masuk</button>
                </div>
            </div>
        </div>
    </div>
</body>