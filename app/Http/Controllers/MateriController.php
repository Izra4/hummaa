<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allMateri = Materi::active()->orderBy('created_at', 'asc')->get();
        
        return view('material.materials-page', compact('allMateri'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('material.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB max
        ]);

        $data = $request->only(['title', 'description', 'duration']);
        
        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('materials', $fileName, 'public');
            
            $data['file_path'] = $filePath;
            $data['file_size'] = $file->getClientOriginalExtension() . ' ' . $this->formatBytes($file->getSize());
        }

        Materi::create($data);

        return redirect()->route('materials')->with('success', 'Materi berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Materi $materi)
    {
        return view('material.show', compact('materi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materi $materi)
    {
        return view('material.edit', compact('materi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Materi $materi)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|string',
            'status' => 'required|in:Progres,Selesai,Belum Dimulai',
            'progress' => 'required|integer|min:0|max:100',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $data = $request->only(['title', 'description', 'duration', 'status', 'progress']);

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
                Storage::disk('public')->delete($materi->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('materials', $fileName, 'public');
            
            $data['file_path'] = $filePath;
            $data['file_size'] = $file->getClientOriginalExtension() . ' ' . $this->formatBytes($file->getSize());
        }

        $materi->update($data);

        return redirect()->route('materials')->with('success', 'Materi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Materi $materi)
    {
        // Delete file if exists
        if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $materi->delete();

        return redirect()->route('materials')->with('success', 'Materi berhasil dihapus!');
    }

    /**
     * Download materi file
     */
    public function download(Materi $materi)
    {
        if (!$materi->file_path || !Storage::disk('public')->exists($materi->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->download(storage_path('app/public/' . $materi->file_path), $materi->title . '.' . pathinfo($materi->file_path, PATHINFO_EXTENSION));
    }

    /**
     * Update progress
     */
    public function updateProgress(Request $request, Materi $materi)
    {
        $request->validate([
            'progress' => 'required|integer|min:0|max:100'
        ]);

        $progress = $request->progress;
        $status = 'Progres';

        if ($progress == 0) {
            $status = 'Belum Dimulai';
        } elseif ($progress >= 100) {
            $status = 'Selesai';
        }

        $materi->update([
            'progress' => $progress,
            'status' => $status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Progress berhasil diperbarui',
            'data' => [
                'progress' => $progress,
                'status' => $status
            ]
        ]);
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($size, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, $precision) . ' ' . $units[$i];
    }
}