<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'category_id', 'target_donation', 'current_donation', 'max_date', 'description', 'image', 'user_id',
    ];

    protected $casts = [
        'target_donation' => 'integer',
        'current_donation' => 'integer',
        'max_date' => 'datetime',
    ];

    /**
     * category
     * 
     * @return void
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * user
     * 
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * donations
     * 
     * @return void
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get total successful donations for this campaign
     *
     * @return int
     */
    public function sumDonation()
    {
        return $this->current_donation ?? 0;
    }

    /**
     * Check if campaign has expired
     * 
     * @return bool
     */
    public function isExpired()
    {
        return $this->max_date->isPast();
    }

    /**
     * image
     *
     * @return Attribute
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => asset('/storage/campaigns/' . $value),
        );
    }
}
