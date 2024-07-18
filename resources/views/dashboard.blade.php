<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row justify-content">
        <div class="col-md">
            <div class="card">
                {{-- <div class="card-header">Welcome...</div> --}}

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="display-4">Selamat Datang, <strong> {{ Auth::user()->name }}! </strong></p>
                    <p class="lead">Kami sangat senang melihat Anda kembali. Semoga hari Anda menyenangkan dan produktif!</p>
                </div>
            </div>
        </div>
    </div>
</br>

    
</div>

@endsection
