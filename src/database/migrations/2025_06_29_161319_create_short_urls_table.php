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
        Schema::create('short_urls', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique()->index();
            $table->text('original_url');
            $table->string('custom_alias')->nullable()->unique();
            $table->boolean('is_active')->default(true);
            $table->integer('click_count')->default(0);
            $table->timestamp('last_accessed_at')->nullable();
          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('short_urls');
    }
};
