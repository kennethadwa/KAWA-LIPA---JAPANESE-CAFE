<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Gallery;
use App\Models\Announcement;
use App\Models\Category; // Imported to safely query category metrics
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
            ]
        ]);
    }
}