<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('code'); // P001, D002, etc.
            $table->foreignId('type')->constrained('teller_categories')->onDelete('cascade');
            $table->enum('status', ['waiting', 'serving', 'done', 'skipped'])->default('waiting');
            $table->date('ticket_date');
            $table->foreignId('teller_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['ticket_date', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
