<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $mapping = [
            'monday' => 'pazartesi',
            'tuesday' => 'sali',
            'wednesday' => 'carsamba',
            'thursday' => 'persembe',
            'friday' => 'cuma',
            'saturday' => 'cumartesi',
            'sunday' => 'pazar',
        ];

        foreach ($mapping as $old => $new) {
            \Illuminate\Support\Facades\DB::table('animes')
                ->where('broadcast_day', $old)
                ->update(['broadcast_day' => $new]);
        }
    }

    public function down(): void
    {
        $mapping = [
            'pazartesi' => 'monday',
            'sali' => 'tuesday',
            'carsamba' => 'wednesday',
            'persembe' => 'thursday',
            'cuma' => 'friday',
            'cumartesi' => 'saturday',
            'pazar' => 'sunday',
        ];

        foreach ($mapping as $new => $old) {
            \Illuminate\Support\Facades\DB::table('animes')
                ->where('broadcast_day', $new)
                ->update(['broadcast_day' => $old]);
        }
    }
};
