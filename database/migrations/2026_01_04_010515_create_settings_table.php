<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, url, file
            $table->timestamps();
        });
        
        // Insert default settings
        DB::table('settings')->insert([
            [
                'key' => 'display_video_url',
                'value' => env('DISPLAY_VIDEO_URL', ''),
                'type' => 'url',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'display_video_file',
                'value' => null,
                'type' => 'file',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'display_marquee_text',
                'value' => env('DISPLAY_MARQUEE_TEXT', 'Welcome to NORSU-GUIHULNGAN Queue System. Please wait for your number to be called.'),
                'type' => 'text',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
