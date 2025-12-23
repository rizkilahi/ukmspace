<section class="mb-5">
    <header class="mb-3">
        <h2 class="text-lg font-medium text-gray-900">Update Profile Information</h2>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Avatar Upload -->
        <div class="form-group mb-3">
            <label for="avatar" class="form-label">Profile Picture</label>
            <div class="d-flex align-items-center gap-3 mb-2">
                @if (auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="rounded-circle"
                        style="width: 80px; height: 80px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                        style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <input type="file" id="avatar" name="avatar" class="form-control" accept="image/*">
            <small class="text-muted">JPG, PNG or GIF (Max 2MB)</small>
            @error('avatar')
                <small class="text-danger d-block">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control"
                value="{{ old('name', auth()->user()->name) }}" required autofocus>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control"
                value="{{ old('email', auth()->user()->email) }}" required>
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" class="form-control"
                value="{{ old('phone', auth()->user()->phone) }}" required>
            @error('phone')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-save"></i> Save Changes
        </button>
    </form>
</section>
