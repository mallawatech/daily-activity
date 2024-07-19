<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
         $reports = Report::with(['dataovertime'])->where('user_id', Auth::id())->get();
        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        // Validasi data yang masuk
        $validatedData = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'activity_log' => 'required',
            'photo.*' => 'image|mimes:jpeg,png,jpg',
        ]);

        // Mendapatkan user yang sedang login
        $user = Auth::user();

        // Pastikan user terotentikasi
        if (!$user) {
            return redirect()->back()->with('error', 'User not authenticated.');
        }

        // Menyimpan laporan dengan user_id yang sesuai
        $report = new Report();
        $report->date = $request->date;
        $report->start_time = $request->start_time;
        $report->end_time = $request->end_time;
        $report->activity_log = $request->activity_log;
        $report->photo = []; // Inisialisasi array untuk menyimpan nama file foto

        // Mengunggah foto dan menyimpan nama file ke dalam array
        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $photo) {
                $filename = $photo->store('photos', 'public');
                $report->photo = array_merge($report->photo, [$filename]);
            }
        }

        // Mengubah array $report->photo menjadi string JSON sebelum menyimpannya
        $report->photo = json_encode($report->photo);

        // Menghubungkan laporan dengan pengguna yang sedang login
        $report->user_id = $user->id;

        $report->save();

        return redirect()->back()->with('success', 'Report added successfully.');
    }

    public function show($id)
    {
        $report = Report::findOrFail($id);
        return view('reports.show', compact('report'));
    }

    public function edit($id)
    {
        $report = Report::findOrFail($id);
        return view('reports.edit', compact('report'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'activity_log' => 'required|string',
            'photo' => 'nullable|array',
            'photo.*' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        $report = Report::findOrFail($id);
        $report->date = $validatedData['date'];
        $report->start_time = $validatedData['start_time'];
        $report->end_time = $validatedData['end_time'];
        $report->activity_log = $validatedData['activity_log'];

        if ($request->hasFile('photo')) {
            $photos = [];
            foreach ($request->file('photo') as $photo) {
                $path = $photo->store('photos', 'public');
                $photos[] = $path;
            }
            $report->photo = json_encode($photos);
        }

        $report->save();

        return redirect()->route('reports.index')->with('success', 'Report updated successfully.');
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->route('reports.index')->with('success', 'Report deleted successfully.');
    }
}
