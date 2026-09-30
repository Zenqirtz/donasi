<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\Donation;
use App\Models\Donatur;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Landing page utama
     */
    public function index()
    {
        $sliders = Slider::where('is_active', true)->orderBy('order')->get();
        $campaigns = Campaign::with('category')->latest()->take(6)->get();
        $totalDonasi = Donation::where('status', 'success')->sum('amount');
        $totalDonatur = Donatur::count();
        $totalCampaign = Campaign::count();

        return view('public.home', compact('sliders', 'campaigns', 'totalDonasi', 'totalDonatur', 'totalCampaign'));
    }

    /**
     * Halaman listing semua campaign
     */
    public function campaigns(Request $request)
    {
        $categories = Category::all();
        $query = Campaign::with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = escapeLike($request->search);
            $query->where('title', 'like', '%'.$search.'%');
        }

        $campaigns = $query->latest()->paginate(9);

        return view('public.campaigns', compact('campaigns', 'categories'));
    }
}
