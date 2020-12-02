<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsInCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams_in_competitions', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('points');
            $table->string('season', 9);
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('competition_id')->constrained();
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
        Schema::dropIfExists('teams_in_competitions');
    }
}
