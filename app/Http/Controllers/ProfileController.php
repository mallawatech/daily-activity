<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        // Ambil data pengguna yang sedang login
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'satker' => 'nullable|string|max:255',
            'kode_eos' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Ambil data pengguna yang sedang login
        $user = Auth::user();

        if (!($user instanceof User)) {
            throw new \Exception('The user is not an instance of User.');
        }

        // Perbarui data pengguna
        $user->name = $request->name;
        $user->email = $request->email;
        $user->satker = $request->satker;
        $user->kode_eos = $request->kode_eos;

        // Perbarui password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Simpan perubahan
        if (method_exists($user, 'save')) {
            $user->save();
        } else {
            throw new \Exception('Method save() does not exist on the user object.');
        }

        // Redirect kembali ke halaman profile dengan pesan sukses
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    
}
