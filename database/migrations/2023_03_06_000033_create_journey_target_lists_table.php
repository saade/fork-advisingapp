<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJourneyTargetListsTable extends Migration
{
    public function up()
    {
        Schema::create('journey_target_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->longText('query')->nullable();
            $table->integer('population')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}