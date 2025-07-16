<section class="mb-5">
    <header class="mb-3">
        <h2 class="text-lg font-medium text-gray-900">Update Profile Information</h2>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required autofocus>
            @error('name')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="form-group mb-3">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}" required>
            @error('phone')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <button type="submit" class="btn btn-primary w-100">Save</button>
    </form>
</section>
