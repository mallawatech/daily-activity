<?php

namespace App\Http\Controllers;

use App\Models\Overtime;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OvertimeController extends Controller
{
    public function index()
    {
        $overtimes = Overtime::where('user_id', Auth::id())->get();
        return view('overtimes.index', compact('overtimes'));
    }

    public function create()
    {
        return view('overtimes.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'report_id' => 'required',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'activity_log' => 'required|string',
            'photos' => 'nullable|array|min:1|max:10',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Parsing waktu mulai dan waktu selesai
        $start = Carbon::parse($request->date . ' ' . $request->start_time);
        $end = Carbon::parse($request->date . ' ' . $request->end_time);

        // Menghitung total waktu kerja dalam menit
        $workMinutes = $start->diffInMinutes($end);

        // Mengonversi menit ke jam
        $workHours = $workMinutes / 60;

        // Mengambil hari dari tanggal yang diberikan
        $dayOfWeek = Carbon::parse($request->date)->dayOfWeek;

        // Inisialisasi total overtime
        $totalOvertime = 0;

        // Logging untuk debug
        Log::info('Start Time: ' . $start);
        Log::info('End Time: ' . $end);
        Log::info('Work Hours: ' . $workHours);
        Log::info('Day of Week: ' . $dayOfWeek);

        // Jika hari adalah Sabtu atau Minggu
        if ($dayOfWeek == Carbon::SATURDAY || $dayOfWeek == Carbon::SUNDAY) {
            $totalOvertime = $workHours; // Total overtime adalah seluruh waktu kerja
        } else {
            // Jika hari adalah hari kerja (Senin-Jumat)
            if ($workHours > 8) {
                $totalOvertime = $workHours - 8; // Total overtime adalah waktu kerja dikurangi 8 jam
            } else {
                // Jika waktu kerja kurang dari atau sama dengan 8 jam, total overtime adalah 0
                $totalOvertime = 0;
            }
        }

        // Logging untuk debug hasil total overtime
        Log::info('Total Overtime: ' . $totalOvertime);

        // Membuat instance baru dari model Overtime
        $overtime = new Overtime();

        // Mengatur properti-properti dari instance Overtime sesuai dengan input
        $overtime->date = $request->date;
        $overtime->day = Carbon::parse($request->date)->format('l');
        $overtime->start_time = $request->start_time;
        $overtime->end_time = $request->end_time;
        $overtime->activity_log = $request->activity_log;
        $overtime->total_overtime = $totalOvertime; // Mengisi total overtime
        $overtime->report_id = $request->report_id;

        // Jika ada foto yang diunggah, simpan foto-foto tersebut
        if ($request->has('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('photos', 'public');
                $photos[] = $path;
            }
            $overtime->photos = json_encode($photos);
        }

        // Menghubungkan overtime dengan pengguna yang sedang login
        $user = Auth::user();
        $overtime->user_id = $user->id;

        // Simpan instance Overtime ke dalam basis data
        $overtime->save();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('overtimes.index')->with('success', 'Overtime record added successfully.');
    }

    

    public function show(Overtime $overtime)
    {
        return view('overtimes.show', compact('overtime'));
    }

    public function edit(Overtime $overtime)
    {
        return view('overtimes.edit', compact('overtime'));
    }

    public function update(Request $request, $overtime_id)
        {
            $request->validate([
                'date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required',
                'activity_log' => 'required|string',
                'photos' => 'nullable|array|min:1|max:10',
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

             $start = Carbon::parse($request->date . ' ' . $request->start_time);
             $end = Carbon::parse($request->date . ' ' . $request->end_time);

            if ($end->lessThan($start)) {
                $end->addDay();
            }

            // Menghitung total waktu kerja dalam menit dan kemudian mengonversinya ke jam
            $workMinutes = $start->diffInMinutes($end, false);
            $workHours = $workMinutes / 60;

            // Mengambil hari dari tanggal yang diberikan
            $dayOfWeek = $start->dayOfWeek;

            // Inisialisasi total overtime
            $totalOvertime = 0;

            // Jika hari adalah Sabtu atau Minggu
            if ($dayOfWeek == Carbon::SATURDAY || $dayOfWeek == Carbon::SUNDAY) {
                $totalOvertime = $workHours; // Total overtime adalah seluruh waktu kerja
            } else {
                // Jika hari adalah hari biasa (Senin-Jumat)
                if ($workHours > 8) {
                    $totalOvertime = $workHours - 8; // Total overtime adalah waktu kerja dikurangi 8 jam
                }
            }

            if ($request->has('photos')) {
                $photos = [];
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('photos', 'public');
                    $photos[] = $path;
                }
                $start_time = Carbon::parse($request->start_time)->format('H:i:s');
                $end_time = Carbon::parse($request->end_time)->format('H:i:s');
    
                $overtime =  Overtime::where('id', $overtime_id)->update([
                    'date' => $request->date,
                    'day' => Carbon::parse($request->date)->format('l'),
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'photos' => json_encode($photos),
                    'activity_log' => $request->activity_log,
                    'total_overtime' => (int) $totalOvertime
                ]);
                if ($overtime){
                    return redirect()->route('overtimes.index')->with('success', 'Overtime record updated successfully.');
                }else{
                    return redirect()->route('overtimes.index')->with('failed', 'Overtime record updated failed.');
                }
            }else{
                $start_time = Carbon::parse($request->start_time)->format('H:i:s');
                $end_time = Carbon::parse($request->end_time)->format('H:i:s');
    
                $overtime =  Overtime::where('id', $overtime_id)->update([
                    'date' => $request->date,
                    'day' => Carbon::parse($request->date)->format('l'),
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'activity_log' => $request->activity_log,
                    'total_overtime' => (int) $totalOvertime
                ]);
                if ($overtime){
                    return redirect()->route('overtimes.index')->with('success', 'Overtime record updated successfully.');
                }else{
                     return redirect()->route('overtimes.index')->with('failed', 'Overtime record updated failed.');
                }
            } 
    }

    public function destroy($id)
    {
        $overtime = Overtime::findOrFail($id);
        $overtime->delete();

        return redirect()->route('overtimes.index')->with('success', 'Overtime record deleted successfully.');
    }
}
