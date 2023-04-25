<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('files')->insert([
            'name' => 'root',
            'slug' => 'root',
            '_lft' => 1,
            '_rgt' => 2,
            'is_folder' => 1,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
            'created_by' => 2,
            'updated_by' => 2
        ]); // Saved as root
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('files')
            ->where('name', '=', 'root')
            ->where('_lft', '=', 1)
            ->delete();
    }
};
