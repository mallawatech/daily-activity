@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if(session('failed'))
    <div class="alert alert-danger">
        {{ session('failed') }}
    </div>
@endif
<div class="card">
    <div class="card-header">
        <h4>Overtime</h4>
    </div>
    <div class="card-body">
        @if($overtimes->isEmpty())
            <p>You have not submitted any overtime yet.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">Date</th>
                            <th class="text-center">Day</th>
                            <th class="text-center">Start Time</th>
                            <th class="text-center">End Time</th>
                            <th class="text-center">Total Overtime</th>
                            <th class="text-center">Activity Log</th>
                            <th class="text-center">Photos</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($overtimes as $overtime)
                            <tr>
                                <td>{{ $overtime->date }}</td>
                                <td>{{ $overtime->day }}</td>
                                <td>{{ $overtime->start_time }}</td>
                                <td>{{ $overtime->end_time }}</td>
                                <td>{{ $overtime->total_overtime }} Jam</td>
                                <td>{{ $overtime->activity_log }}</td>
                                <td>
                                    @if($overtime->photos)
                                        @php
                                            $photos = json_decode($overtime->photos, true);
                                        @endphp
                                        @if(is_array($photos) && count($photos) > 0)
                                            <div class="row">
                                                @foreach($photos as $photo)
                                                    <div class="col-md-3 mb-3">
                                                        <img src="{{ asset('storage/' . $photo) }}" alt="Photo" width="100" height="100">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p>No photos available.</p>
                                        @endif
                                    @else
                                        <p>No photos uploaded.</p>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn-circle btn-warning btn-sm" data-toggle="modal" data-target="#editOvertimeModal-{{ $overtime->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form id="deleteForm-{{ $overtime->id }}" action="{{ route('overtimes.destroy', $overtime->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-circle btn-danger btn-sm" onclick="confirmDelete('{{ $overtime->id }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="editOvertimeModal-{{ $overtime->id }}" tabindex="-1" aria-labelledby="editOvertimeModalLabel-{{ $overtime->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editOvertimeModalLabel-{{ $overtime->id }}">Edit Overtime</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="editForm-{{ $overtime->id }}" action="{{ route('overtimes.update', $overtime->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <label for="date-{{ $overtime->id }}">Date</label>
                                                    <input type="date" class="form-control" id="date-{{ $overtime->id }}" name="date" value="{{ $overtime->date }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="start_time-{{ $overtime->id }}">Start Time</label>
                                                    <input type="time" class="form-control" id="start_time-{{ $overtime->id }}" name="start_time" value="{{ $overtime->start_time }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="end_time-{{ $overtime->id }}">End Time</label>
                                                    <input type="time" class="form-control" id="end_time-{{ $overtime->id }}" name="end_time" value="{{ $overtime->end_time }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="activity_log-{{ $overtime->id }}">Activity Log</label>
                                                    <textarea class="form-control" id="activity_log-{{ $overtime->id }}" name="activity_log" rows="3" required>{{ $overtime->activity_log }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="photos-{{ $overtime->id }}">Photos</label>
                                                    <input type="file" class="form-control-file" id="photos-{{ $overtime->id }}" name="photos[]" multiple>
                                                    @php
                                                        $photos = json_decode($overtime->photos, true);
                                                    @endphp
                                                    @if(is_array($photos) && count($photos) > 0)
                                                        <div class="row mt-2">
                                                            @foreach($photos as $photo)
                                                                <div class="col-md-3 mb-3">
                                                                    <img src="{{ asset('storage/' . $photo) }}" alt="Photo" width="100" height="100">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    function confirmDelete(overtimeId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm-' + overtimeId).submit();
            }
        });
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 1500
        });
    @endif

    console.error({{ json_encode($errors->all()) }});
</script>
@endsection
