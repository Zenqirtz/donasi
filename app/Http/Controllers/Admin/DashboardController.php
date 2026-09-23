<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donatur;


class DashboardController extends Controller
{    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //donatur
        $donaturs = Donatur::count();

        //campaign
        $campaigns = Campaign::count();

        //donations total success
        $donations = Donation::where('status', 'success')->sum('amount');

        //donations pending count
        $donationsPending = Donation::where('status', 'pending')->count();

        //recent donations
        $recentDonations = Donation::with(['campaign', 'donatur'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.index', [
            'donaturs' => $donaturs,
            'campaigns' => $campaigns,
            'donations' => $donations,
            'donationsPending' => $donationsPending,
            'recentDonations' => $recentDonations,
        ]);
    }
}
