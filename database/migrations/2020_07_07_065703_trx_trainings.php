<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TrxTrainings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trx_trainings', function (Blueprint $table) {
            $table->id();
            $table->string('training_name');
            $table->integer('training_year');
            $table->timestamps();
        });

        Schema::table('trx_trainings', function (Blueprint $table) {
            $table->foreignId('nip')->constrained('participants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
