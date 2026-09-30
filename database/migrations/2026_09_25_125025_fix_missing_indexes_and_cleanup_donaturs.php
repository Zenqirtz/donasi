<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Migration ini idempotent karena pernah diterapkan langsung ke live DB
     * (lihat commit 999dd68), jadi setiap perubahan dicek dulu sebelum dieksekusi.
     */
    public function up(): void
    {
        $this->ensureDonationIndexes();
        $this->dropDonaturLegacyColumns();
    }

    /**
     * Pastikan index yang dibutuhkan donations ada. Kolom sudah dibuat di
     * 2025_09_05_011012_create_donations_table.php, migration ini hanya
     * menutup kemungkinan live DB dibuat tanpa index tersebut.
     */
    protected function ensureDonationIndexes(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            if (! $this->hasIndex('donations', 'donations_invoice_unique')) {
                $table->unique('invoice');
            }

            if (! $this->hasIndex('donations', 'donations_status_index')) {
                $table->index('status');
            }

            if (! $this->hasIndex('donations', 'donations_campaign_id_status_index')) {
                $table->index(['campaign_id', 'status']);
            }
        });
    }

    /**
     * Buang kolom donatur sisa migrasi lama. Kolom ini tidak pernah ada di
     * 2025_09_05_010925_create_donaturs_table.php, jadi dicek satu per satu.
     */
    protected function dropDonaturLegacyColumns(): void
    {
        $legacy = array_values(array_filter(
            ['email_verified_at', 'password', 'remember_token'],
            fn (string $column) => Schema::hasColumn('donaturs', $column)
        ));

        if ($legacy === []) {
            return;
        }

        Schema::table('donaturs', function (Blueprint $table) use ($legacy) {
            $table->dropColumn($legacy);
        });
    }

    /**
     * Check apakah sebuah index sudah ada di tabel tersebut.
     *
     * Menggunakan query mentah, bukan Doctrine, supaya tidak butuh doctrine/dbal.
     */
    protected function hasIndex(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        $rows = $driver === 'sqlite'
            ? $connection->select("SELECT name FROM sqlite_master WHERE type = 'index' AND tbl_name = ?", [$table])
            : $connection->select(
                'SELECT DISTINCT INDEX_NAME AS index_name FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
                [$table]
            );

        foreach ($rows as $row) {
            $name = $row->index_name ?? $row->name ?? null;

            if ($name === $index) {
                return true;
            }
        }

        return false;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            if ($this->hasIndex('donations', 'donations_campaign_id_status_index')) {
                $table->dropIndex(['campaign_id', 'status']);
            }
        });
    }
};
