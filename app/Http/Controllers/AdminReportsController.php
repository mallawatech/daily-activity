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
        $reports = Report::with('user')->get();
        Log::info('Reports data:', $reports->toArray());
        return view('admin.dashboard', compact('reports'));
    }
}

