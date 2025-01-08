<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - UKM Space</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f9f9f9;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 400px;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            font-size: 14px;
            color: #555;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .submit-btn {
            width: 100%;
            padding: 10px;
            background: #2F55D4;
            color: white;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background: #2345b6;
        }

        .back-to-login {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .back-to-login a {
            color: #2F55D4;
            text-decoration: none;
            font-weight: 500;
        }

        .back-to-login a:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Forgot Password</h1>
        <p>Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
        <!-- Session Status -->
        @if (session('status'))
            <p class="success">{{ session('status') }}</p>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" placeholder="Enter your email address" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                    <div class="error">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <button type="submit" class="submit-btn">Send Password Reset Link</button>
        </form>

        <div class="back-to-login">
            Remember your password? <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </div>
</body>
</html>
