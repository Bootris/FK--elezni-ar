<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Extra profile data shown in the player detail view on the first-team page. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->unsignedSmallInteger('weight_kg')->nullable()->after('height_cm');
            $table->string('preferred_foot', 5)->nullable()->after('weight_kg'); // left / right / both
            $table->string('birth_place')->nullable()->after('birth_date');
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn(['weight_kg', 'preferred_foot', 'birth_place']);
        });
    }
};
