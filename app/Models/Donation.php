<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Donation extends Model
{
    use HasFactory;
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'invoice', 'campaign_id', 'donatur_id', 'amount', 'pray', 'status', 'snap_token', 'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'integer',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::updating(function ($donation) {
            // Auto set paid_at when status becomes success
            if ($donation->isDirty('status') && $donation->status === 'success' && !$donation->paid_at) {
                $donation->paid_at = now();
            }
        });

        static::updated(function ($donation) {
            // Update campaign current_donation when status changed to success
            if ($donation->isDirty('status')) {
                if ($donation->status === 'success') {
                    $donation->campaign->increment('current_donation', $donation->amount);
                } elseif ($donation->getOriginal('status') === 'success') {
                    $donation->campaign->decrement('current_donation', $donation->amount);
                }
            }
        });
    }

    /**
     * campaign
     * 
     * @return void
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * donatur
     * 
     * @return void
     */
    public function donatur()
    {
        return $this->belongsTo(Donatur::class);
    }

    /**
     * createAt
     * 
     * @return Attribute
     */
    protected function createdAt(): Attribute
    {   
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->Format('d-M-Y'),
        );
    }

    /**
     * updateAt
     * 
     * @return Attribute
     */

    protected function updatedAt(): Attribute
    {   
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->Format('d-M-Y'),
        );
    }
}
