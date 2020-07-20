<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TrxResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trx_results', function (Blueprint $table) {
            $table->id();
            // trainings
            $table->string('training_id')->nullable();
            $table->string('name')->nullable();
            $table->datetime('start')->nullable();
            $table->datetime('end')->nullable();
            $table->string('hours')->nullable();
            $table->string('days')->nullable();
            $table->string('satker')->nullable();
            $table->string('type')->nullable();
            $table->string('cost')->nullable();
            $table->string('cost_detail')->nullable();
            $table->string('elearning')->nullable();
            $table->string('test')->nullable();
            $table->string('class')->nullable();
            // students
            $table->string('id')->nullable();
            $table->string('name')->nullable();
            $table->string('nip')->nullable();
            $table->string('nrp_nik')->nullable();
            $table->string('rank_class')->nullable();
            $table->string('born')->nullable();
            $table->datetime('birthday')->nullable();
            $table->string('gender')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('office_address')->nullable();
            $table->string('education')->nullable();
            $table->string('education_desc')->nullable();
            $table->string('position')->nullable();
            $table->string('position_desc')->nullable();
            $table->string('married')->nullable();
            $table->string('religion')->nullable();
            $table->string('main_unit')->nullable();
            $table->string('eselon2')->nullable();
            $table->string('eselon3')->nullable();
            $table->string('eselon4')->nullable();
            $table->string('satker')->nullable();
            $table->string('test')->nullable();
            $table->string('graduate_status')->nullable();
            $table->string('activity')->nullable();
            $table->string('presence')->nullable();
            $table->string('pre_test')->nullable();
            $table->string('post_test')->nullable();
            // certificate
            $table->string('number')->nullable();
            $table->datetime('date')->nullable();
            $table->string('execution_value')->nullable();
            $table->string('trainer_value')->nullable();
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
