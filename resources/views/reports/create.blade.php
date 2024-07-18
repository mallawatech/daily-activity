@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Add New Report</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <input type="time" name="start_time" id="start_time" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="end_time">End Time</label>
                    <input type="time" name="end_time" id="end_time" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="activity_log">Activity Log</label>
                    <textarea name="activity_log" id="activity_log" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label for="photo">Photo</label>
                    <input type="file" name="photo" id="photo" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Add Report</button>
            </form>
        </div>
    </div>
</div>
@endsection
