<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donatur extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'avatar',
    ];

    /**
     * donations
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Cari donatur berdasarkan email, termasuk yang di-soft-delete.
     *
     * Kolom email punya constraint UNIQUE, jadi donatur yang sudah dihapus
     * tetap menempati baris itu. Tanpa withTrashed, firstOrCreate akan
     * mencoba insert email yang sama dan gagal dengan unique violation.
     * Donatur yang ditemukan dalam keadaan terhapus dipulihkan kembali.
     */
    public static function findOrCreateByEmail(string $email, string $name): self
    {
        $email = mb_strtolower(trim($email));

        $donatur = static::withTrashed()->where('email', $email)->first();

        if ($donatur) {
            $donatur->restore();

            if ($donatur->name !== $name) {
                $donatur->update(['name' => $name]);
            }

            return $donatur;
        }

        return static::create([
            'email' => $email,
            'name' => $name,
        ]);
    }

    /**
     * avatar
     */
    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => filled($value)
                ? asset('/storage/donaturs/'.$value)
                : 'https://ui-avatars.com/api/?name='.urlencode((string) $this->name).'&background=4e73df&color=ffffff&size=100',
        );
    }
}
