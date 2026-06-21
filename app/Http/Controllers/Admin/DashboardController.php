<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Gallery;
use App\Models\Announcement;
use App\Models\Category;
use App\Models\CalendarNote; // Imported for calendar persistence
use Illuminate\Http\Request; // Imported for saving notes payload
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the workspace metrics overview engine.
     */
    public function index()
    {
        // 1. Calculate active total items inside catalog matrix
        $totalMenuItems = Menu::count();

        // 2. Safely fetch unique category metrics from the correct model
        $totalCategories = Category::count();

        // 3. Count live system announcements elements
        $totalAnnouncements = Announcement::count();

        // 4. Extract total assets from gallery directory index broken down by type
        $totalGalleryItems = Gallery::count();
        $galleryImagesCount = Gallery::where('media_type', 'image')->count();
        $galleryVideosCount = Gallery::where('media_type', 'video')->count();

        // 5. Fetch all calendar notes and key them by their date string
        // Turns data array into index matchable format: {'2026-06-21': {title: '...', body: '...'}}
        $notes = CalendarNote::all()->keyBy('note_date');

        return Inertia::render('Dashboard', [
            'stats' => [
                'menuItemsCount' => $totalMenuItems,
                'categoriesCount' => $totalCategories,
                'announcementsCount' => $totalAnnouncements,
                'gallery' => [
                    'total' => $totalGalleryItems,
                    'images' => $galleryImagesCount,
                    'videos' => $galleryVideosCount,
                ]
            ],
            'notes' => $notes // Injected into Inertia page props mapping
        ]);
    }

    /**
     * Handle the creation, updating, or structural cleaning of operational logs.
     */
    public function saveCalendarNote(Request $request)
    {
        $request->validate([
            'note_date' => 'required|date',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
        ]);

        // If both input fields are wiped clean, delete row execution record
        if (empty($request->title) && empty($request->body)) {
            CalendarNote::where('note_date', $request->note_date)->delete();
            return back();
        }

        // Upsert operations structure engine execution
        CalendarNote::updateOrCreate(
            ['note_date' => $request->note_date],
            [
                'title' => $request->title,
                'body' => $request->body
            ]
        );

        return back();
    }
}