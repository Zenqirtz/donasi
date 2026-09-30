<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Kolom sliders.order sebelumnya punya UNIQUE constraint sementara
     * controller selalu menyimpan nilai default 0, sehingga slider kedua
     * gagal disimpan. Constraint dilepas, penomoran urut_now()
     * ditangani SliderController::store.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('sliders', 'order')) {
            return;
        }

        if ($this->hasUniqueOnOrder()) {
            Schema::table('sliders', function (Blueprint $table) {
                $table->dropUnique(['order']);
            });
        }
    }

    /**
     * Check apakah constraint UNIQUE pada kolom order masih ada.
     */
    protected function hasUniqueOnOrder(): bool
    {
        $connection = Schema::getConnection();

        $rows = $connection->getDriverName() === 'sqlite'
            ? $connection->select("PRAGMA index_list('sliders')")
            : $connection->select(
                'SELECT INDEX_NAME AS index_name, NON_UNIQUE AS non_unique
                 FROM information_schema.STATISTICS
                 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
                ['sliders', 'order']
            );

        foreach ($rows as $row) {
            // SQLite: kolom "unique" = 1 bila index itu UNIQUE.
            // MySQL: NON_UNIQUE = 0 bila index itu UNIQUE.
            $unique = $row->non_unique ?? $row->unique ?? 0;

            if ((int) $unique === 0) {
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
        // Penomoran urut tidak dikembalikan ke UNIQUE karena default 0
        // membuat insert kedua selalu bentrok.
    }
};
