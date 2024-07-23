<!DOCTYPE html>
<html>
<head>
    <title>Overtime Report PDF</title>
    <style>
        body {
            font-family: 'Arial, sans-serif';
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table, .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .table th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        img {
            display: block;
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="table-responsive">
        <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Satker</th>
                    <th class="text-center">Kode EOS</th>
                    <th class="text-center">Periode</th>
                </tr>
            </thead>
            <tbody>
                @if($overtimes->isNotEmpty())
                    <tr>
                        <td class="text-center">{{ $overtimes->first()->user->name }}</td>
                        <td class="text-center">{{ $overtimes->first()->user->satker }}</td>
                        <td class="text-center">{{ $overtimes->first()->user->kode_eos }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($overtimes->first()->date)->format('F Y') }}</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="3" class="text-center">OVERTIME</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="card-body">
        <div class="table-responsive" style="overflow-x: auto;">
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
                        <td>
                            @if(isset($overtime->photos) && !empty($overtime->photos))
                                @php
                                    $photos = json_decode($overtime->photos, true);
                                @endphp
                                @if(is_array($photos) && count($photos) > 0)
                                    <img src="{{ asset('storage/' . $photos[0]) }}" alt="photo" width="50" height="50">
                                @else
                                    <p>No photos available</p>
                                @endif
                            @else
                                <p>No photos uploaded</p>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
