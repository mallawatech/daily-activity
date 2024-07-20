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
        ]);

        // Ambil data pengguna yang sedang login
        $user = Auth::user();

        // Perbarui data pengguna
        $user->name = $request->name;
        $user->email = $request->email;
        $user->satker = $request->satker;
        $user->kode_eos = $request->kode_eos;

        // Simpan perubahan
        $user->save();

        // Redirect kembali ke halaman profile dengan pesan sukses
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        // Validasi input
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Ambil data pengguna yang sedang login
        $user = Auth::user();

        // Perbarui password pengguna
        $user->password = Hash::make($request->password);

        // Simpan perubahan
        $user->save();

        // Redirect kembali ke halaman profile dengan pesan sukses
        return redirect()->route('profile.show')->with('success', 'Password updated successfully.');
    }


}


