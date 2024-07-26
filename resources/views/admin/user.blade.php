@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-primary">User List</h5>
        <div class="h5 mb-0 font-weight-bold text-primary">Total User : {{ \App\Models\User::count() }} User</div>
    </div>
        <div class="card shadow mb-4">
            {{-- <div class="card-header py-3 d-flex justify-content-between align-items-center">
                
            </div> --}}
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Name</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Satker</th>
                                <th class="text-center">Kode EOS</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->satker }}</td>
                                <td>{{ $user->kode_eos }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $user->id }})">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-4 mb-4">
                        {{ $users->links() }} <!-- Link pagination -->
                    </div>
                </div>
            </div>
        </div>
</div>


    <script>
        function confirmDelete(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + userId).submit();
                }
            })
        }
    </script>
@endsection
