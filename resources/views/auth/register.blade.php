<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - UKM Space</title>
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

        .form-container {
            width: 100%;
            max-width: 500px;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-container h1 {
            font-size: 28px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 5px;
            color: rgba(0, 0, 0, 0.8);
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        .form-group .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            font-weight: 600;
            color: white;
            background: #2F55D4;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background: #2345b6;
        }

        .login-prompt {
            text-align: center;
            margin-top: 10px;
            font-size: 16px;
        }

        .login-prompt a {
            color: #2F55D4;
            text-decoration: none;
            font-weight: 500;
        }

        .login-prompt a:hover {
            text-decoration: underline;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Create an Account</h1>
        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <!-- Role Selection -->
            <div class="form-group">
                <label for="role">Register As</label>
                <select id="role" name="role" required onchange="toggleForm(this.value)">
                    <option value="">Select Role</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="ukm" {{ old('role') == 'ukm' ? 'selected' : '' }}>UKM</option>
                </select>
                @error('role')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- User Form -->
            <div id="user-form" class="hidden">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" value="{{ old('name') }}">
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">
                    @error('phone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password">
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password">
                    @error('password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- UKM Form -->
            <div id="ukm-form" class="hidden">
                <div class="form-group">
                    <label for="name">UKM Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter UKM name" value="{{ old('name') }}">
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter UKM email" value="{{ old('email') }}">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">
                    @error('phone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" placeholder="Enter UKM address">
                    @error('address')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password">
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password">
                    @error('password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="submit-btn">Register</button>

            <div class="login-prompt">
                Already have an account? <a href="{{ route('login') }}">Login here</a>
            </div>
        </form>
    </div>

    <script>
        function toggleForm(role) {
            document.getElementById('user-form').classList.add('hidden');
            document.getElementById('ukm-form').classList.add('hidden');
            if (role === 'user') {
                document.getElementById('user-form').classList.remove('hidden');
            } else if (role === 'ukm') {
                document.getElementById('ukm-form').classList.remove('hidden');
            }
        }

        // Pastikan form yang sesuai ditampilkan setelah reload jika validasi gagal
        const selectedRole = "{{ old('role') }}";
        if (selectedRole) toggleForm(selectedRole);
    </script>
</body>
</html>
