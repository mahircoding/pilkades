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
        Schema::create('voter_import_issues', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('voter_import_batch_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('row_number');
            $table->string('nik_masked')->nullable();
            $table->string('type');
            $table->text('message');
            $table->timestamps();

            $table->index(['voter_import_batch_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voter_import_issues');
    }
};
