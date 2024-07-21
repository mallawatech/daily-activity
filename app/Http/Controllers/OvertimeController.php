<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Overtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'photos.*' => 'image|mimes:jpeg,png,jpg',
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

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'report_id' => 'required',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'activity_log' => 'required|string',
            'photos' => 'nullable|array|min:1|max:10',
            'photos.*' => 'image|mimes:jpeg,png,jpg',
        ]);

        // Temukan instance Overtime berdasarkan ID
        $overtime = Overtime::find($id);
        if (!$overtime) {
            Log::error('Overtime record not found: ' . $id);
            return redirect()->route('overtimes.index')->with('error', 'Overtime record not found.');
        }

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

        // Log untuk debug
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

        // Log untuk debug hasil total overtime
        Log::info('Total Overtime: ' . $totalOvertime);

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
            // Hapus foto lama jika ada
            if ($overtime->photos) {
                $oldPhotos = is_string($overtime->photos) ? json_decode($overtime->photos) : $overtime->photos;
                foreach ($oldPhotos as $photo) {
                    Storage::disk('public')->delete($photo);
                }
            }

            $photos = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('photos', 'public');
                $photos[] = $path;
            }
            $overtime->photos = json_encode($photos);
        }

        // Simpan instance Overtime ke dalam basis data
        try {
            $overtime->save();
            Log::info('Overtime record updated successfully: ' . $overtime);
        } catch (\Exception $e) {
            Log::error('Failed to update Overtime record: ' . $e->getMessage());
        }

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('overtimes.index')->with('success', 'Overtime record updated successfully.');
    }

    public function destroy($id)
    {
        $overtime = Overtime::findOrFail($id);
        $overtime->delete();

        return redirect()->route('overtimes.index')->with('success', 'Overtime record deleted successfully.');
    }
}
