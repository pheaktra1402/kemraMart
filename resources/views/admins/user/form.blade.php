<div class="row g-4">

    <div class="col-md-4">
        <label class="form-label">Avatar</label>

        <div class="border rounded d-flex align-items-center justify-content-center bg-light mb-2"
             style="height: 200px; overflow: hidden;">
            <span class="display-1 text-muted">
                {{ strtoupper(substr($user->name ?? '?', 0, 1)) }}
            </span>
        </div>

        @isset($user)
            <div class="small text-muted">
                Joined {{ $user->created_at->format('Y-m-d') }}
            </div>
        @endisset
    </div>

    <div class="col-md-8">
        <div class="row g-3">
            <div class="col-md-7">
                <label class="form-label">Name</label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name ?? '') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-5">
                <label class="form-label">Role</label>
                <select name="role" class="form-select @error('role') is-invalid @enderror">
                    <option value="customer" @selected(old('role', $user->role ?? 'customer') === 'customer')>Customer</option>
                    <option value="admin" @selected(old('role', $user->role ?? 'customer') === 'admin')>Admin</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Email</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email ?? '') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-dark">Save</button>
    <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>