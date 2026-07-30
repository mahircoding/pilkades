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
        Schema::create('candidates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('election_period_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('ballot_number')->nullable();
            $table->string('name');
            $table->string('nik_hash', 64);
            $table->text('nik_encrypted');
            $table->string('nik_masked');
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('photo_path')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->string('verification_status')->default('submitted');
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->unique(['election_period_id', 'ballot_number']);
            $table->index(['election_period_id', 'verification_status']);
            $table->index('nik_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
