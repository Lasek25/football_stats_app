<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsInMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams_in_matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('goals')->default(0);
            $table->unsignedTinyInteger('corners')->default(0);
            $table->unsignedTinyInteger('yellow_cards')->default(0);
            $table->unsignedTinyInteger('red_cards')->default(0);
            $table->unsignedTinyInteger('fouls')->default(0);
            $table->unsignedTinyInteger('offsides')->default(0);
            $table->unsignedTinyInteger('shots_on_goal')->default(0);
            $table->foreignId('match_id')->constrained()->onDelete('cascade');
            $table->foreignId('teams_in_competition_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams_in_matches');
    }
}
