<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UKM Space</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto:wght@500&display=swap" rel="stylesheet">
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
            background-color: #f9f9f9;
        }

        .login-container {
            width: 400px;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-text h1 {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .welcome-text p {
            font-size: 18px;
            color: rgba(0, 0, 0, 0.6);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            color: rgba(0, 0, 0, 0.8);
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
            height: 45px;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            font-size: 14px;
            color: rgba(0, 0, 0, 0.8);
        }

        .forgot-password {
            text-align: right;
            color: #0061D2;
            font-size: 14px;
            text-decoration: underline;
            margin-bottom: 20px;
        }

        .login-btn {
            width: 100%;
            height: 45px;
            background: #2F55D4;
            border-radius: 10px;
            color: white;
            font-size: 18px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            margin-bottom: 15px;
        }

        .signup-prompt {
            text-align: center;
            font-size: 14px;
            color: rgba(0, 0, 0, 0.6);
        }

        .signup-prompt span {
            font-weight: 600;
            color: #2F55D4;
            cursor: pointer;
        }

        .back-btn {
            width: 100%;
            height: 45px;
            background: #f0f0f0;
            border-radius: 10px;
            color: #2F55D4;
            font-size: 18px;
            font-weight: 500;
            border: 1px solid #dcdcdc;
            cursor: pointer;
            text-align: center;
            line-height: 45px;
            margin-top: 10px;
        }

        .back-btn:hover {
            background: #e5e5e5;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="welcome-text">
            <h1>Welcome!</h1>
            <p>Please login to continue</p>
        </div>
        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <div class="forgot-password">
                <a href="{{ route('password.request') }}">Forgot password?</a>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
        <div class="signup-prompt">
            Don't have an account yet? <a href="{{ route('register') }}"><span>Sign up NOW!</span></a>
        </div>
        <a href="{{ route('home') }}" class="back-btn">Back to Home</a>
    </div>
</body>
</html>
