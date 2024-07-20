@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Profile</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Kolom 1: Informasi Profil -->
        <div class="col-lg-6">
            <table class="table table-borderless">
                <tr>
                    <th>Name</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Satker</th>
                    <td>{{ $user->satker }}</td>
                </tr>
                <tr>
                    <th>Kode EOS</th>
                    <td>{{ $user->kode_eos }}</td>
                </tr>
            </table>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editProfileModal">
                Edit Profile
            </button>

            <!-- Modal Edit Profile -->
            <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('POST')
            
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
            
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
            
                                <div class="form-group">
                                    <label for="satker">Satker</label>
                                    <input id="satker" type="text" class="form-control @error('satker') is-invalid @enderror" name="satker" value="{{ old('satker', $user->satker) }}">
                                    @error('satker')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
            
                                <div class="form-group">
                                    <label for="kode_eos">Kode EOS</label>
                                    <input id="kode_eos" type="text" class="form-control @error('kode_eos') is-invalid @enderror" name="kode_eos" value="{{ old('kode_eos', $user->kode_eos) }}">
                                    @error('kode_eos')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
            
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom 2: Update Password -->
        <div class="col-lg-6">
            <h3>Update Password</h3>
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#updatePasswordModal">
                Update Password
            </button>

            <!-- Modal Update Password -->
            <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('POST')
            
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
            
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
            
                                <div class="form-group">
                                    <label for="satker">Satker</label>
                                    <input id="satker" type="text" class="form-control @error('satker') is-invalid @enderror" name="satker" value="{{ old('satker', $user->satker) }}">
                                    @error('satker')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
            
                                <div class="form-group">
                                    <label for="kode_eos">Kode EOS</label>
                                    <input id="kode_eos" type="text" class="form-control @error('kode_eos') is-invalid @enderror" name="kode_eos" value="{{ old('kode_eos', $user->kode_eos) }}">
                                    @error('kode_eos')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
            
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
