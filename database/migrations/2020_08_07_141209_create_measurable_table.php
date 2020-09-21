<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasurableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurable', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name')->unique()
                ->comment('Name of the measurable');
            $table->string('class')->unique()
                ->comment('Class name of the measurable item');
            $table->string('unit')
                ->comment('unit of the measurable, like °C, Hz, ect.');
            $table->boolean('active')
                ->comment('status of the measurable');
            $table->string('table')
                ->comment('database table name of the measurable');
            $table->integer('frequency')
                ->comment('update frequency in ms, that defines how often the measurable object need to be checked');
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
        Schema::dropIfExists('measurable');
    }
}
