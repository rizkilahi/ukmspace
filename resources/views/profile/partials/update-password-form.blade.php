<section class="mb-5">
    <header class="mb-3">
        <h2 class="text-lg font-medium text-gray-900">Update Password</h2>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="form-group mb-3">
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" class="form-control" required>
            @error('current_password')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="form-group mb-3">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
            @error('password')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="form-group mb-3">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            @error('password_confirmation')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">Update Password</button>
    </form>
</section>
