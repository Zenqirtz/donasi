<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    /**
     * Status yang menandakan donasi sudah terkumpul.
     */
    public const STATUS_SUCCESS = 'success';

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'invoice', 'campaign_id', 'donatur_id', 'amount', 'pray', 'status', 'snap_token', 'paid_at',
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
            // Isi paid_at otomatis saat status berubah menjadi success.
            if ($donation->isDirty('status') && $donation->status === self::STATUS_SUCCESS && ! $donation->paid_at) {
                $donation->paid_at = now();
            }
        });

        static::updated(function ($donation) {
            if (! $donation->wasChanged('status')) {
                return;
            }

            $original = $donation->getOriginal('status');
            $isNowSuccess = $donation->status === self::STATUS_SUCCESS;
            $wasSuccess = $original === self::STATUS_SUCCESS;

            if ($isNowSuccess && ! $wasSuccess) {
                $donation->syncCampaignTotal($donation->amount);
            } elseif ($wasSuccess && ! $isNowSuccess) {
                $donation->syncCampaignTotal(-$donation->amount);
            }
        });
    }

    /**
     * Terapkan delta ke current_donation campaign tanpa membuat nilai negatif.
     *
     * Kolom current_donation di schema(unsigned) tidak bisa menerima nilai
     * negatif, jadi decrement di-clamp ke nol. Campaign yang sudah di-soft-delete
     * sengaja dilewati karena relasi campaign() memfilter deleted_at.
     */
    public function syncCampaignTotal(int $delta): void
    {
        $campaign = $this->campaign;

        if (! $campaign) {
            return;
        }

        if ($delta >= 0) {
            $campaign->increment('current_donation', $delta);

            return;
        }

        $remaining = max(0, (int) $campaign->current_donation - abs($delta));

        $campaign->forceFill(['current_donation' => $remaining])->saveQuietly();
    }

    /**
     * campaign
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * donatur
     */
    public function donatur()
    {
        return $this->belongsTo(Donatur::class);
    }

    /**
     * Tanggal donasi siap tampil, mis. "05-Sep-2025".
     */
    protected function tanggal(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? \Illuminate\Support\Carbon::parse($value)->format('d-M-Y') : '-',
        );
    }
}
