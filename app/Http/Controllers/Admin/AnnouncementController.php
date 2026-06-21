<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::latest()->get();
        
        return Inertia::render('Admin/Announcements/Index', [
            'announcements' => $announcements
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|string|in:draft,published',
            'expires_at' => 'nullable|date|after_or_equal:today',
        ]);

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')
            ->with('message', 'Announcement created successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|string|in:draft,published',
            'expires_at' => 'nullable|date|after_or_equal:today',
        ]);

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')
            ->with('message', 'Announcement updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('message', 'Announcement deleted successfully!');
    }
}