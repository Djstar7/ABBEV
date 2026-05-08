<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Media;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display dashboard
     */
    public function index()
    {
        // Users statistics
        $stats['users'] = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'users' => User::where('role', 'user')->count(),
            'users_today' => User::whereDate('created_at', today())->count(),
        ];

        // Media statistics
        $stats['media'] = [
            'total' => Media::count(),
            'movies' => Media::where('type', 'movie')->count(),
            'series' => Media::where('type', 'series')->count(),
            'recent' => Media::whereDate('created_at', '>=', Carbon::now()->subDays(7))->count(),
        ];

        // Categories statistics
        $stats['categories'] = [
            'total' => Category::count(),
        ];

        // Chart data for last 30 days
        $chartData = [
            'media' => [
                'labels' => $this->getLast30DaysLabels(),
                'movies' => $this->getLast30DaysData(Media::where('type', 'movie')),
                'series' => $this->getLast30DaysData(Media::where('type', 'series')),
            ],
            'users' => [
                'labels' => $this->getLast30DaysLabels(),
                'data' => $this->getLast30DaysData(User::query()),
            ],
        ];

        // Top categories
        $topCategories = Category::withCount('media')
            ->orderBy('media_count', 'desc')
            ->take(5)
            ->get();

        // Recent media
        $recentMedia = Media::with('category')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'chartData', 'topCategories', 'recentMedia'));
    }

    /**
     * Get last 30 days labels
     */
    private function getLast30DaysLabels()
    {
        $labels = [];
        for ($i = 29; $i >= 0; $i--) {
            $labels[] = Carbon::now()->subDays($i)->format('d M');
        }
        return $labels;
    }

    /**
     * Get last 30 days data
     */
    private function getLast30DaysData($query)
    {
        $data = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $count = (clone $query)->whereDate('created_at', $date)->count();
            $data[] = $count;
        }
        return $data;
    }
}
