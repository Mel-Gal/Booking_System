<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('appointments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('client_id')->constrained()->onDelete('cascade');
        $table->foreignId('staff_id')->constrained('users');
        $table->string('service_type');
        $table->date('appointment_date');
        $table->time('appointment_time');
        $table->enum('status', ['Scheduled', 'Confirmed', 'Completed', 'Cancelled', 'No Show'])->default('Scheduled');
        $table->text('notes')->nullable();
        $table->foreignId('created_by')->constrained('users');
        
        // Added columns from your other migration
        $table->timestamp('scheduled_at')->nullable();
        $table->timestamp('confirmed_at')->nullable();
        $table->timestamp('completed_at')->nullable();
        $table->timestamp('cancelled_at')->nullable();
        $table->timestamp('no_show_at')->nullable();
        
        $table->timestamps();
        $table->softDeletes(); // Keep this here
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('appointments');
}
};
