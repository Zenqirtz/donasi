<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    /**
     * index
     */
    public function index()
    {
        $campaigns = Campaign::with('category')
            ->when(request()->filled('q'), function ($query) {
                $query->where('title', 'like', '%'.escapeLike(request()->q).'%');
            })
            ->latest()
            ->paginate(10);

        return view('admin.campaign.index', compact('campaigns'));
    }

    /**
     * create
     */
    public function create()
    {
        $categories = Category::latest()->get();

        return view('admin.campaign.create', compact('categories'));
    }

    /**
     * store
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2000',
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'target_donation' => 'required|numeric|min:1',
            'max_date' => 'required|date|after:today',
            'description' => 'required|string',
        ]);

        $image = $request->file('image');
        $image->storeAs('public/campaigns', $image->hashName());

        $campaign = Campaign::create([
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['title']),
            'category_id' => $validated['category_id'],
            'target_donation' => $validated['target_donation'],
            'max_date' => $validated['max_date'],
            'description' => $validated['description'],
            'user_id' => auth()->id(),
            'image' => $image->hashName(),
        ]);

        return redirect()->route('admin.campaign.index')
            ->with($campaign ? ['success' => 'Data Berhasil Disimpan!'] : ['error' => 'Data Gagal Disimpan!']);
    }

    /**
     * edit
     */
    public function edit(Campaign $campaign)
    {
        $categories = Category::latest()->get();

        return view('admin.campaign.edit', compact('campaign', 'categories'));
    }

    /**
     * update
     */
    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2000',
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'target_donation' => 'required|numeric|min:1',
            'max_date' => 'required|date',
            'description' => 'required|string',
        ]);

        // Target tidak boleh diturunkan di bawah donasi yang sudah terkumpul.
        if ((int) $validated['target_donation'] < (int) $campaign->current_donation) {
            return back()->withErrors([
                'target_donation' => 'Target donasi tidak boleh lebih kecil dari donasi yang sudah terkumpul ('
                    .moneyFormat($campaign->current_donation).').',
            ])->withInput();
        }

        $attributes = [
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['title'], $campaign->id),
            'category_id' => $validated['category_id'],
            'target_donation' => $validated['target_donation'],
            'max_date' => $validated['max_date'],
            'description' => $validated['description'],
            'user_id' => auth()->id(),
        ];

        if ($request->hasFile('image')) {
            Storage::disk('local')->delete('public/campaigns/'.basename($campaign->getRawOriginal('image')));

            $image = $request->file('image');
            $image->storeAs('public/campaigns', $image->hashName());

            $attributes['image'] = $image->hashName();
        }

        $campaign->update($attributes);

        return redirect()->route('admin.campaign.index')
            ->with(['success' => 'Data Berhasil Diupdate!']);
    }

    /**
     * destroy
     */
    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id);

        Storage::disk('local')->delete('public/campaigns/'.basename($campaign->getRawOriginal('image')));
        $campaign->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

    /**
     * Slug unik dari judul, ditambahkan sufiks bila slug sudah dipakai.
     */
    protected function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title, '-') ?: 'campaign';
        $slug = $base;
        $suffix = 1;

        while (Campaign::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $base.'-'.(++$suffix);
        }

        return $slug;
    }
}
