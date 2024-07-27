<?php

namespace App\Http\Controllers;

use pdf;
use JSPDF;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Report;
use App\Models\Overtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{   
    public function index()
    {
        $user = Auth::user(); // Mendapatkan pengguna yang sedang login

        if ($user && $user->role == 'user') // Memeriksa apakah pengguna terautentikasi dan perannya 'user'
        {
            return view('dashboard');
        }
        else if ($user && $user->role == 'admin') // Memeriksa apakah pengguna terautentikasi dan perannya 'admin'
        {
            $reports = Report::with('user')->simplePaginate(5);
            $overtimes = Overtime::with('user')->simplePaginate(5); // Mengambil data lembur dengan relasi user
            //Log::info('Reports data:', $reports->toArray());
            //Log::info('Overtimes data:', $overtimes->toArray());
            return view('admin.dashboard', compact('reports', 'overtimes'));
        }
        else
        {
            return redirect('/login'); // Mengarahkan ke halaman login jika tidak terautentikasi atau peran tidak sesuai
        }
    }

    public function pdfReport(Request $request)
    {
        $search = $request->input('search');
         $reports = Report::whereHas('user', function ($query) use ($search) {
        $query->where('name', 'like', '%' . $search . '%');
        })->get();

        // Menggunakan view Laravel dan mengubahnya menjadi HTML
        $html = view('admin.report', compact('reports'))->render();

        // Membuat instance Mpdf dan menulis HTML ke dalamnya
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);

        // Menghasilkan file PDF
        $mpdf->Output('Report.pdf', 'I'); // 'I' berarti menampilkan file di browser
    }
    

    public function pdfOvertimeReport()
    {
        $overtimes = Overtime::with('user')->get();

        // Menggunakan view Laravel dan mengubahnya menjadi HTML
        $html = view('admin.overtime', compact('overtimes'))->render();

        // Membuat instance Mpdf dan menulis HTML ke dalamnya
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($html);

        // Menghasilkan file PDF
        $mpdf->Output('Overtime.pdf', 'I'); // 'I' berarti menampilkan file di browser
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $reports = Report::whereHas('user', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->simplePaginate(5);

        $overtimes = Overtime::whereHas('user', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->simplePaginate(5);

        return view('admin.dashboard', compact('reports', 'overtimes'));
    }
}

