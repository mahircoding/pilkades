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
        Schema::create('election_periods', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->date('election_date')->nullable();
            $table->dateTime('registration_starts_at')->nullable();
            $table->dateTime('registration_ends_at')->nullable();
            $table->dateTime('dps_starts_at')->nullable();
            $table->dateTime('dps_ends_at')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->index(['status', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('election_periods');
    }
};
