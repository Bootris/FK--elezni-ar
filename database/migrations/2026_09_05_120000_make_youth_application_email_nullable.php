<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Parents may apply with a phone number only; the email field stays but is optional. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('youth_applications', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Fails on rows without an email instead of silently dropping data.
        Schema::table('youth_applications', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
        });
    }
};
