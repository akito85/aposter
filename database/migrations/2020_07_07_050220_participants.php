<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Participants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique();
            $table->string('name');
            $table->string('satker');
            $table->string('class');
            $table->string('status');
            $table->string('note');
            $table->string('nickname');
            $table->string('prefix_degree');
            $table->string('suffix_degree');
            $table->string('ktp');
            $table->string('npwp');
            $table->string('pob');
            $table->datetime('dob');
            $table->string('gender');
            $table->string('homepage');
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->string('work_address');
            $table->string('work_phone');
            $table->string('work_fax');
            $table->string('marrital_status');
            $table->string('campus_name');
            $table->string('work_position_id');
            $table->string('work_position');
            $table->string('work_organisation');
            $table->string('no_sk');
            $table->string('tmt_sk');
            $table->string('religion');
            $table->string('bracket');
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
        //
    }
}
