<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportPagesTable extends Migration
{
    public function up()
    {
        Schema::create('support_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->longText('body');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
