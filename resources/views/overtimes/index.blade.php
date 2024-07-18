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
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createOvertimeModal">
                    <i class="fas fa-solid fa-plus"></i>
                </button>
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
                    @foreach($overtimes as $index => $overtime)
                        @if($overtime->user_id == Auth::id())
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
                                        $photos = json_decode($overtime->photos, true); // Dekode JSON menjadi array
                                    @endphp
                                    @if(is_array($photos) && count($photos) > 0)
                                        <div class="row">
                                            @foreach($photos as $photo)
                                                <div class="col-md-3 mb-3">
                                                    <img src="{{ asset('storage/' . $photo) }}" alt="Photo" class="img-thumbnail" width="100" height="100">
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

                            <!-- Edit Overtime Modal -->
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
                                            <form action="{{ route('overtimes.update', $overtime->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                              
                                                <div class="form-group">
                                                    <label for="date-{{ $overtime->id }}">Date</label>
                                                    <input type="date" class="form-control" id="date-{{ $overtime->id }}" name="date" value="{{ $overtime->date }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="day-{{ $overtime->id }}">Day</label>
                                                    <input type="text" class="form-control" id="day-{{ $overtime->id }}" name="day" value="{{ $overtime->day }}" required readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="start_time-{{ $overtime->id }}">Start Time</label>
                                                    <input type="time" class="form-control" id="start_time-{{ $overtime->id }}" name="start_time" value="{{ old('start_time', $overtime->start_time) }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="end_time-{{ $overtime->id }}">End Time</label>
                                                    <input type="time" class="form-control" id="end_time-{{ $overtime->id }}" name="end_time" value="{{ old('end_time', $overtime->end_time) }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="activity_log-{{ $overtime->id }}">Activity Log</label>
                                                    <textarea class="form-control" id="activity_log-{{ $overtime->id }}" name="activity_log" rows="3" required>{{ $overtime->activity_log }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="photos-{{ $overtime->id }}">Photos</label>
                                                    <input type="file" class="form-control-file" id="photos-{{ $overtime->id }}" name="photos[]" multiple></br>
                                                    @if ($overtime->photos)
                                                        @foreach(json_decode($overtime->photos, true) as $photo)
                                                            <img src="{{ asset('storage/' . $photo) }}" alt="photo" width="100" height="100">
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
            </div>
        </div>

        <!-- Create Overtime Modal -->
        <div class="modal fade" id="createOvertimeModal" tabindex="-1" aria-labelledby="createOvertimeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createOvertimeModalLabel">Add New Overtime</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('overtimes.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="date-create">Date</label>
                                <input type="date" class="form-control" id="date-create" name="date" required>
                            </div>
                            <div class="form-group">
                                <label for="day-create">Day</label>
                                <input type="text" class="form-control" id="day-create" name="day" readonly>
                            </div>
                            <div class="form-group">
                                <label for="start_time-create">Start Time</label>
                                <input type="time" class="form-control" id="start_time-create" name="start_time" required>
                            </div>
                            <div class="form-group">
                                <label for="end_time-create">End Time</label>
                                <input type="time" class="form-control" id="end_time-create" name="end_time" required>
                            </div>
                            <div class="form-group">
                                <label for="activity_log-create">Activity Log</label>
                                <textarea class="form-control" id="activity_log-create" name="activity_log" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="photos-create">Photos</label>
                                <input type="file" class="form-control-file" id="photos-create" name="photos[]" multiple required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Overtime</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('date-create').addEventListener('change', function () {
                let dateValue = this.value;
                let dayValue = new Date(dateValue).toLocaleDateString('en-US', { weekday: 'long' });
                document.getElementById('day-create').value = dayValue;
            });

            @foreach($overtimes as $overtime)
                document.getElementById('date-{{ $overtime->id }}').addEventListener('change', function () {
                    let dateValue = this.value;
                    let dayValue = new Date(dateValue).toLocaleDateString('en-US', { weekday: 'long' });
                    document.getElementById('day-{{ $overtime->id }}').value = dayValue;
                });
            @endforeach
        });

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
