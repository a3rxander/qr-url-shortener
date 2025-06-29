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
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->string('identifier', 20)->unique()->index();
            $table->text('content');
            $table->string('type', 50)->default('text'); // text, url, wifi, vcard, etc.
            $table->string('format', 10)->default('png'); // png, svg, jpg
            $table->integer('size')->default(300);
            $table->string('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
