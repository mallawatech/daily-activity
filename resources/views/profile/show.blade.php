@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Profile</h1>

    <div class="row">
        <div class="col-lg-8">
            <!-- Update Profile Information -->
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <form method="POST" action="{{ route('user-profile-information.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', Auth::user()->name) }}" required autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                        @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>

                <hr class="my-4">
            @endif

            <!-- Update Password -->
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <form method="POST" action="{{ route('user-password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required>
                        @error('current_password')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm New Password</label>
                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Password</button>
                </form>

                <hr class="my-4">
            @endif

            <!-- Two-Factor Authentication -->
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <form method="POST" action="{{ url('user/two-factor-authentication') }}">
                    @csrf
                    @if (Auth::user()->two_factor_secret)
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Disable Two-Factor</button>
                    @else
                        <button type="submit" class="btn btn-primary">Enable Two-Factor</button>
                    @endif
                </form>

                <hr class="my-4">
            @endif

            <!-- Logout Other Browser Sessions -->
            <form method="POST" action="{{ route('other-browser-sessions.logout') }}">
                @csrf
                <button type="submit" class="btn btn-warning">Logout Other Browser Sessions</button>
            </form>

            <hr class="my-4">

            <!-- Delete User -->
            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <form method="POST" action="{{ route('current-user.destroy') }}">
                    @csrf
                    @method('DELETE')

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                        @error('password')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-danger">Delete Account</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
