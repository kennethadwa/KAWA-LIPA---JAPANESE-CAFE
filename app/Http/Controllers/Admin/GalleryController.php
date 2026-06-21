<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GalleryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Gallery/Index', [
            'items' => Gallery::latest()->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,webm|max:51200', // Max 50MB
            'type' => 'required|string|in:customer,crew,event',
            'featured' => 'required|boolean'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // 1. Save directly into storage/app/public/gallery
            $path = $file->store('gallery', 'public');

            // 2. Automatically deduce file classification
            $mime = $file->getMimeType();
            $mediaType = str_contains($mime, 'video') ? 'video' : 'image';

            Gallery::create([
                'title' => $request->title,
                'file_path' => $path,
                'media_type' => $mediaType,
                'type' => $request->type,
                'featured' => $request->featured,
            ]);
        }

        return redirect()->route('admin.gallery.index')->with('message', 'Media added successfully!');
    }

    public function destroy(Gallery $gallery)
    {
        // Delete the physical underlying asset source file safely from disk storage
        if (Storage::disk('public')->exists($gallery->file_path)) {
            Storage::disk('public')->delete($gallery->file_path);
        }

        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('message', 'Media item removed.');
    }
}