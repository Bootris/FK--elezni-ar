<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Football-club content on top of the site-core tables (posts, categories,
 * contact_messages, settings, users): first-team squad, staff, youth
 * selections, fixtures/results, league table, photo gallery and the youth
 * enrolment inbox.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('youth_selections', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // U-9, U-11, Kadeti…
            $table->string('slug')->unique();
            $table->string('birth_years')->nullable();   // "2016/2017"
            $table->text('description')->nullable();
            $table->string('training_schedule')->nullable(); // "Pon, Sre, Pet · 17:00–18:30"
            $table->string('training_venue')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('accepting_applications')->default(true);
            $table->integer('sort_order')->default(0);
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });

        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedSmallInteger('shirt_number')->nullable();
            $table->string('position', 4)->default('MF'); // GK / DF / MF / FW
            $table->string('nationality')->nullable();
            $table->date('birth_date')->nullable();
            $table->unsignedSmallInteger('height_cm')->nullable();
            $table->unsignedSmallInteger('joined_year')->nullable();
            $table->string('previous_club')->nullable();
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_captain')->default(false);
            $table->boolean('from_academy')->default(false);
            $table->integer('sort_order')->default(0);
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });

        Schema::create('staff_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('role');                                  // "Šef stručnog štaba"
            $table->string('department')->default('first_team');     // first_team / youth / club
            $table->foreignId('youth_selection_id')->nullable()->constrained()->nullOnDelete();
            $table->string('licence')->nullable();                   // "UEFA A"
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });

        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->string('team_type')->default('first');           // first / youth
            $table->foreignId('youth_selection_id')->nullable()->constrained()->nullOnDelete();
            $table->string('competition');
            $table->string('round')->nullable();
            $table->dateTime('kickoff_at');
            $table->string('opponent');
            $table->boolean('is_home')->default(true);
            $table->unsignedSmallInteger('our_score')->nullable();
            $table->unsignedSmallInteger('their_score')->nullable();
            $table->string('venue')->nullable();
            $table->string('status')->default('scheduled');          // scheduled / live / finished / postponed
            $table->foreignId('post_id')->nullable()->constrained()->nullOnDelete(); // match report
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->index(['team_type', 'kickoff_at']);
        });

        Schema::create('standing_rows', function (Blueprint $table) {
            $table->id();
            $table->string('competition')->default('first');         // table key
            $table->unsignedSmallInteger('position')->default(1);
            $table->string('team');
            $table->unsignedSmallInteger('played')->default(0);
            $table->unsignedSmallInteger('won')->default(0);
            $table->unsignedSmallInteger('drawn')->default(0);
            $table->unsignedSmallInteger('lost')->default(0);
            $table->unsignedSmallInteger('goals_for')->default(0);
            $table->unsignedSmallInteger('goals_against')->default(0);
            $table->unsignedSmallInteger('points')->default(0);
            $table->string('form', 10)->nullable();                  // "WWDLW"
            $table->boolean('is_club')->default(false);              // highlight our row
            $table->timestamps();
        });

        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('image');
            $table->string('album')->default('club');                // first_team / youth / club
            $table->integer('sort_order')->default(0);
            $table->boolean('visible')->default(true);
            $table->timestamps();
        });

        Schema::create('youth_applications', function (Blueprint $table) {
            $table->id();
            $table->string('child_name');
            $table->unsignedSmallInteger('birth_year');
            $table->string('parent_name');
            $table->string('phone');
            $table->string('email');
            $table->foreignId('youth_selection_id')->nullable()->constrained()->nullOnDelete();
            $table->text('note')->nullable();
            $table->string('status')->default('new');                // new / contacted / enrolled / rejected
            $table->text('admin_note')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('youth_applications');
        Schema::dropIfExists('photos');
        Schema::dropIfExists('standing_rows');
        Schema::dropIfExists('matches');
        Schema::dropIfExists('staff_members');
        Schema::dropIfExists('players');
        Schema::dropIfExists('youth_selections');
    }
};
