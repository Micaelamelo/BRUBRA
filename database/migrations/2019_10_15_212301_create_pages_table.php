<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
  //      DB::statement('ALTER TABLE pages ADD COLUMN frequent []');
  //      DB::statement('ALTER TABLE pages ADD COLUMN puntajes []');
  //      DB::statement('ALTER TABLE pages ADD COLUMN contacts []');

        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->String('name');
            $table->text('frequent');
            $table->text('puntajes');
            $table->text('contacts');
            $table->String('vendedor');            
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
        Schema::dropIfExists('pages');
    }
}
