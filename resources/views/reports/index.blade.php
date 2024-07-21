@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3"">
            <h5 class="m-0 font-weight-bold text-primary">Report Table</h5>
        </div>
        
        <div class="card-body">
            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createReportModal">
                <i class="fas fa-solid fa-plus"></i>
            </button>
            @if($reports->isEmpty())
                <p>Silahkan Upload Data Anda Terlebi Dahulu</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">Date</th>
                                <th class="text-center">Start Time</th>
                                <th class="text-center">End Time</th>
                                <th class="text-center">Activity</th>
                                <th class="text-center">Photos</th>
                                <th class="text-center">Overtimes</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($report->date)->format('Y-m-d') }}</td>
                                    <td class="text-center">{{ $report->start_time }}</td>
                                    <td class="text-center">{{ $report->end_time }}</td>
                                    <td>{{ $report->activity_log }}</td>
                                    <td class="text-center">
                                        @if($photos = json_decode($report->photo, true))
                                            <img src="{{ asset('storage/' . $photos[0]) }}" alt="photo" width="100" height="100">
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($report->dataovertime)
                                            {{ $report->dataovertime->total_overtime }}
                                        @else
                                            <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createOvertimeModal-{{ $report->id }}">
                                                <i class="fas fa-solid fa-plus"></i>
                                            </button>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn-circle btn-info btn-sm" data-toggle="modal" data-target="#detailModal-{{ $report->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn-circle btn-warning btn-sm" data-toggle="modal" data-target="#editModal-{{ $report->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form id="deleteForm-{{ $report->id }}" action="{{ route('reports.destroy', $report->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-circle btn-danger btn-sm" onclick="confirmDelete('{{ $report->id }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <!-- Detail Modal Report-->
                                <div class="modal fade" id="detailModal-{{ $report->id }}" tabindex="-1" aria-labelledby="detailModalLabel-{{ $report->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="detailModalLabel-{{ $report->id }}">Detail Report</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($report->date)->format('Y-m-d') }}</p>
                                                        <p><strong>Start Time:</strong> {{ $report->start_time }}</p>
                                                        <p><strong>End Time:</strong> {{ $report->end_time }}</p>
                                                        <p><strong>Activity Log:</strong> {{ $report->activity_log }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Photos:</strong></p>
                                                        @foreach(json_decode($report->photo, true) as $photo)
                                                            <img src="{{ asset('storage/' . $photo) }}" alt="photo" width="150" height="150" class="mb-2">
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                

                                <!-- Edit Modal Report -->
                                <div class="modal fade" id="editModal-{{ $report->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $report->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel-{{ $report->id }}">Edit Report</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="editForm-{{ $report->id }}" action="{{ route('reports.update', $report->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="date">Date</label>
                                                        <input type="date" class="form-control" id="date" name="date" value="{{ $report->date }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="start_time">Start Time</label>
                                                        <input type="time" class="form-control" id="start_time" name="start_time" value="{{ $report->start_time }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="end_time">End Time</label>
                                                        <input type="time" class="form-control" id="end_time" name="end_time" value="{{ $report->end_time }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="activity_log">Activity Log</label>
                                                        <textarea class="form-control" id="activity_log" name="activity_log" rows="3" required>{{ $report->activity_log }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="photo">Photo</label>
                                                        <input type="file" class="form-control-file" id="photo" name="photo[]" multiple></br>
                                                        @foreach(json_decode($report->photo, true) as $photo)
                                                            <img src="{{ asset('storage/' . $photo) }}" alt="photo" width="100" height="100">
                                                        @endforeach
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Create Overtime Modal -->
                                <div class="modal fade" id="createOvertimeModal-{{ $report->id }}" tabindex="-1" aria-labelledby="createOvertimeModalLabel" aria-hidden="true">
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
                                                        <input type="text" class="form-control" value="{{ $report->id }}" name="report_id" hidden>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Create Modal Report -->
    <div class="modal fade" id="createReportModal" tabindex="-1" role="dialog" aria-labelledby="createReportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createReportModalLabel">Add New Report</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createReportForm" action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="form-group">
                            <label for="start_time">Start Time</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <div class="form-group">
                            <label for="end_time">End Time</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" required>
                        </div>
                        <div class="form-group">
                            <label for="activity_log">Activity Log</label>
                            <textarea class="form-control" id="activity_log" name="activity_log" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="photo">Photo</label>
                            <input type="file" class="form-control-file" id="photo" name="photo[]" multiple>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Report</button>
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
        });

        function confirmDelete(reportId) {
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
                    document.getElementById('deleteForm-' + reportId).submit();
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
    </script>
@endsection
