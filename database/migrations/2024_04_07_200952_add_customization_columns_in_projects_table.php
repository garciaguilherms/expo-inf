<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('background_image')->nullable();
            $table->string('background_color')->nullable();
            $table->string('font_family')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('background_image');
            $table->dropColumn('background_color');
            $table->dropColumn('font_family');
        });
    }
};
