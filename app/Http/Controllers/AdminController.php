<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Report;
use App\Models\Overtime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    
    public function dashboard()
    {
        return view('admin.dashboard'); // Pastikan Anda memiliki view 'admin.dashboard'
    }

}
