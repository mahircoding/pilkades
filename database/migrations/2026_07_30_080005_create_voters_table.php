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
        Schema::create('voters', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('election_period_id')->constrained()->cascadeOnDelete();
            $table->string('nik_hash', 64);
            $table->text('nik_encrypted');
            $table->string('nik_masked');
            $table->string('name');
            $table->string('gender', 20)->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('rt', 3)->nullable();
            $table->string('rw', 3)->nullable();
            $table->string('tps_label')->nullable();
            $table->string('list_status')->default('dps');
            $table->foreignId('import_batch_id')->nullable()->constrained('voter_import_batches')->nullOnDelete();
            $table->timestamps();

            $table->unique(['election_period_id', 'nik_hash']);
            $table->index(['election_period_id', 'list_status']);
            $table->index('import_batch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voters');
    }
};
