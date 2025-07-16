<section class="mb-5">
    <header class="mb-3">
        <h2 class="text-lg font-medium text-gray-900">Delete Account</h2>

        <p class="mt-1 text-sm text-gray-600">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" class="mt-4">
        @csrf
        @method('delete')

        <div class="form-group mb-3">
            <p class="text-danger">Once your account is deleted, all of its resources and data will be permanently deleted. Please be certain.</p>
        </div>

        <div class="form-group mb-3">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
            @error('password')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <button type="submit" class="btn btn-danger w-100">Delete Account</button>
    </form>
</section>
