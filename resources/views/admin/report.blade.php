<!DOCTYPE html>
<html>
<head>
    <title>Report PDF</title>
    <style>
        /* Tambahkan CSS untuk styling PDF */
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

        .signature-table {
            margin-top: 40px;
            width: 100%;
            border: 0;
        }

        .signature-table td {
            text-align: center;
            padding: 30px 0;
        }
    </style>
</head>
<body>
    {{-- <h1>Report</h1> --}}

    <!-- Table for User Information -->
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
                @if($reports->isNotEmpty())
                    <tr>
                        <td class="text-center">{{ $reports->first()->user->name }}</td>
                        <td class="text-center">{{ $reports->first()->user->satker }}</td>
                        <td class="text-center">{{ $reports->first()->user->kode_eos }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($reports->first()->date)->format('F Y') }}</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="3" class="text-center">REPORT</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Table for Reports -->
    <div class="table-responsive">
    <table class="table table-bordered" width="100%" cellspacing="0">
        <thead>
            <tr>
                {{-- <th class="text-center">Name</th> --}}
                <th class="text-center">Date</th>
                <th class="text-center">Start Time</th>
                <th class="text-center">End Time</th>
                <th class="text-center">Activity Log</th>
                <th class="text-center">Photos</th> <!-- Ubah label kolom -->
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                {{-- <td>{{ $report->user->name }}</td> --}}
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
                            @foreach($photos as $photo)
                                <img src="{{ asset('storage/' . $photo) }}" alt="photo" width="100" height="100" style="margin: 5px;">
                            @endforeach
                        @else
                            No photos
                        @endif
                    @else
                        No photos
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

    <!-- Table for Signatures -->
    <div class="table-responsive">
        <table class="signature-table" width="100%">
            <tr>
                <td>Dibuat Oleh</td>
                <td>Disetujui Oleh</td>
                <td>Direktur</td>
            </tr>
            <tr>
                @if($reports->isNotEmpty())
                    <tr>
                        <td>{{ $reports->first()->user->name }}</td>
                        <td>_________________</td>
                        <td>_________________</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="3" class="text-center">         </td>
                    </tr>
                @endif
            </tr>
        </table>
    </div>
</body>
</html>
