<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mengambil data anggota dan melakukan pencarian
        $members = Member::when($request->search, function ($query, $search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis_nim', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
        })->latest()->paginate(10)->withQueryString();

        return inertia('Members/Index', [
            'members' => $members,
            'search' => $request->search,
        ]);
    }

    /**
     * Endpoint JSON untuk live search anggota (dipanggil oleh Axios).
     */
    public function search(Request $request)
    {
        // Kata kunci dibaca dari alamat, misalnya /members/search?q=ani
        $kataKunci = $request->query('q', '');

        // nama ATAU nis_nim yang cocok, maksimal 10 baris
        $members = Member::where(function ($query) use ($kataKunci) {
                $query->where('nama', 'like', "%{$kataKunci}%")
                      ->orWhere('nis_nim', 'like', "%{$kataKunci}%");
            })
            ->latest()
            ->limit(10)
            ->get(['id', 'nama', 'nis_nim', 'no_hp', 'foto']);

        return response()->json($members);
    }

    /**
     * Endpoint JSON untuk validasi realtime: apakah NIS/NIM masih tersedia?
     */
    public function cekNis(Request $request)
    {
        // NIS/NIM yang sedang diketik, misalnya /members/cek-nis?nis_nim=2023001
        $nisNim = $request->query('nis_nim', '');

        // abaikan = id anggota yang sedang diedit (kosong pada form tambah)
        $abaikanId = $request->query('abaikan');

        // Apakah ada anggota lain yang sudah memakai NIS/NIM ini?
        $sudahDipakai = Member::where('nis_nim', $nisNim)
            ->when($abaikanId, function ($query, $id) {
                // Jika ada id yang diabaikan, jangan hitung anggota itu sendiri
                $query->where('id', '!=', $id);
            })
            ->exists();

        // Dibalas sebagai JSON, misalnya {"tersedia":true}
        return response()->json([
            'tersedia' => ! $sudahDipakai,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Members/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data anggota dan foto
        $validated = $request->validate([
            'nama' => 'required|max:255',
            'nis_nim' => 'required|max:50|unique:members',
            'no_hp' => 'nullable|digits_between:8,15',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        // Simpan foto jika pengguna mengunggah foto
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('members', 'public');
        }

        // Simpan data anggota ke database
        Member::create($validated);

        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        return inertia('Members/Edit', [
            'member' => $member,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        // Validasi data anggota
        $validated = $request->validate([
            'nama' => 'required|max:255',
            'nis_nim' => 'required|max:50|unique:members,nis_nim,' . $member->id,
            'no_hp' => 'nullable|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        // Periksa apakah pengguna mengunggah foto baru
        if ($request->hasFile('foto')) {

            // Hapus foto lama jika tersedia
            if ($member->foto) {
                Storage::disk('public')->delete($member->foto);
            }

            // Simpan foto baru
            $validated['foto'] = $request->file('foto')->store('members', 'public');

        } else {
            // Pertahankan foto lama jika tidak ada foto baru
            unset($validated['foto']);
        }

        // Perbarui data anggota
        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        // Hapus foto dari storage jika tersedia
        if ($member->foto) {
            Storage::disk('public')->delete($member->foto);
        }

        // Hapus data anggota dari database
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil dihapus');
    }
}