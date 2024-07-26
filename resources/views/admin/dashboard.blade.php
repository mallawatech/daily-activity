@extends('layouts.app')

@section('content')
    {{-- card  --}}
    <div class="row">
        <!-- Card User -->
        <div class="col-xl-4 col-md-6 mb-4">
            <a href="{{ route('admin.users') }}" style="text-decoration:none;">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Users</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card Date -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Current Date and Time</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Task -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Users with Reports</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Report::count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end card  --}}

    <!-- Search -->
    <form class="d-sm-inline-block form-inline mr-auto my-2 my-md-0 mw-100 navbar-search" method="GET" action="{{ route('admin.search') }}">
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search By Name" id="search" name="search" value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>
    <!-- Search -->
</br></br>

    <!-- DataTales Report -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Reports</h6>
        </div>
        <div class="card-body">
            {{-- PDF --}}
            <a href="{{ route('admin.report', ['search' => request('search')]) }}" class="btn btn-primary" id="downloadPdfBtn">
                <i class="fas fa-solid fa-file-pdf"></i>
            </a>
            
            </br></br>
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-bordered" id="dataTableReports" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Start Time</th>
                            <th class="text-center">End Time</th>
                            <th class="text-center">Activity Log</th>
                            <th class="text-center">Photo</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        <tr>
                            <td>{{ $report->user->name }}</td>
                            <td>{{ $report->date }}</td>
                            <td>{{ $report->start_time }}</td>
                            <td>{{ $report->end_time }}</td>
                            <td>{{ $report->activity_log }}</td>
                            <td class="text-center">
                                @if($report->photo)
                                    @php
                                        $photos = json_decode($report->photo, true);
                                    @endphp
                                    @if(count($photos) > 0)
                                        <img src="{{ asset('storage/' . $photos[0]) }}" alt="photo" width="50" height="50">
                                    @else
                                        No photo
                                    @endif
                                @else
                                    No photo
                                @endif
                            </td">
                            <td class="text-center">
                                <!-- Add action buttons if needed -->
                                <button type="button" class="btn-circle btn-info btn-sm" data-toggle="modal" data-target="#viewModal{{ $report->id }}">
                                    <i class="fas fa-info"></i>
                                </button>
                                {{-- <a href="{{ route('reports.edit', $report->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a> --}}
                                <form action="{{ route('reports.destroy', $report->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-circle btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4 mb-4">
                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </div>
    {{-- End DataTales Report --}}

    <!-- Modals for viewing details -->
    @foreach($reports as $report)
    <!-- Modal for Report -->
    <div class="modal fade" id="viewModal{{ $report->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel{{ $report->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel{{ $report->id }}">Report Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h2>{{ $report->user->name }}</h2>
                                    <p><strong>Date: {{ $report->date }} </strong></p>
                                    <p><strong>Start Time: {{ $report->start_time }}</strong></p>
                                    <p><strong>End Time:{{ $report->end_time }}</strong></p>
                                    <p><strong>Activity Log:{{ $report->activity_log }}</strong></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <p><strong>Photos:</strong></p>
                                    @if($report->photo)
                                        <div class="row">
                                            @foreach(json_decode($report->photo, true) as $photo)
                                                <div class="col-md-4 mb-3">
                                                    <img src="{{ asset('storage/' . $photo) }}" alt="photo" class="img-fluid">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p>No photos</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach


    <!-- Tabel Overtimes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Overtimes</h6>
        </div>
        <div class="card-body">
            <a href="{{ route('admin.overtime') }}" class="btn btn-primary" id="downloadPdfBtn">
                <i class="fas fa-solid fa-file-pdf"></i>
            </a>
            <div class="table-responsive mt-2" style="overflow-x: auto;">
                <table class="table table-bordered" id="dataTableOvertimes" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Day</th>
                            <th class="text-center">Start Time</th>
                            <th class="text-center">End Time</th>
                            <th class="text-center">Total Overtime</th>
                            <th class="text-center">Activity Log</th>
                            <th class="text-center">Photo</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($overtimes as $overtime)
                        <tr>
                            <td>{{ $overtime->user->name }}</td>
                            <td>{{ $overtime->date }}</td>
                            <td>{{ \Carbon\Carbon::parse($overtime->date)->format('l') }}</td>
                            <td>{{ $overtime->start_time }}</td>
                            <td>{{ $overtime->end_time }}</td>
                            <td>{{ $overtime->total_overtime }} Jam</td>
                            <td>{{ $overtime->activity_log }}</td>
                            <td class="text-center">
                                @if($overtime->photos)
                                    @php
                                        $photos = is_string($overtime->photos) ? json_decode($overtime->photos, true) : $overtime->photos;
                                    @endphp
                                    @if(is_array($photos) && count($photos) > 0)
                                        <img src="{{ asset('storage/' . $photos[0]) }}" alt="photo" width="50" height="50">
                                    @else
                                        No photo
                                    @endif
                                @else
                                    No photo
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn-circle btn-info btn-sm" data-toggle="modal" data-target="#viewOvertimeModal{{ $overtime->id }}">
                                    <i class="fas fa-info"></i>
                                </button>
                                <form action="{{ route('overtimes.destroy', $overtime->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-circle btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
    
                        <!-- Modal -->
                        <div class="modal fade" id="viewOvertimeModal{{ $overtime->id }}" tabindex="-1" role="dialog" aria-labelledby="viewOvertimeModalLabel{{ $overtime->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewOvertimeModalLabel{{ $overtime->id }}">Overtime Details</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p><strong>Name:</strong> {{ $overtime->user->name }}</p>
                                                        <p><strong>Date:</strong> {{ $overtime->date }}</p>
                                                        <p><strong>Day:</strong> {{ \Carbon\Carbon::parse($overtime->date)->format('l') }}</p>
                                                        <p><strong>Start Time:</strong> {{ $overtime->start_time }}</p>
                                                        <p><strong>End Time:</strong> {{ $overtime->end_time }}</p>
                                                        <p><strong>Total Overtime:</strong> {{ $overtime->total_overtime }} jam</p>
                                                        <p><strong>Activity Log:</strong> {{ $overtime->activity_log }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p><strong>Photos:</strong></p>
                                                        @if($overtime->photos)
                                                            @php
                                                                $photos = is_string($overtime->photos) ? json_decode($overtime->photos, true) : $overtime->photos;
                                                            @endphp
                                                            @if(is_array($photos) && count($photos) > 0)
                                                                <div class="row">
                                                                    @foreach($photos as $photo)
                                                                        <div class="col-md-4 mb-3">
                                                                            <img src="{{ asset('storage/' . $photo) }}" alt="photo" class="img-fluid">
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <p>No photos</p>
                                                            @endif
                                                        @else
                                                            <p>No photos</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4 mb-4">
                    {{ $overtimes->links() }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- End Tabel Overtimes -->
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


