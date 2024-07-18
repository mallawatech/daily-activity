<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AdminReportsController extends Controller
{
    public function dashboard()
    {
        // Mengambil semua data dari tabel reports beserta relasi user
        $reports = Report::with('user')->get();
        
        // Debug: Menampilkan data ke dalam log Laravel
        Log::info('Reports data:', $reports->toArray());
        
        // Mengirim data ke view
        return view('admin.dashboard', compact('reports'));
    }
}

