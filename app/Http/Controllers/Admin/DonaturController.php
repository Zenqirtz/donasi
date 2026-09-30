<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donatur;

class DonaturController extends Controller
{
    /**
     * index
     */
    public function index()
    {
        $donaturs = Donatur::when(request()->filled('q'), function ($query) {
                $search = escapeLike(request()->q);

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('admin.donatur.index', compact('donaturs'));
    }
}
