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
            $table->string('name')->nullable();
            $table->string('satker')->nullable();
            $table->string('class')->nullable();
            $table->string('status')->nullable();
            $table->string('note')->nullable();
            $table->string('nickname')->nullable();
            $table->string('prefix_degree')->nullable();
            $table->string('suffix_degree')->nullable();
            $table->string('ktp')->nullable();
            $table->string('npwp')->nullable();
            $table->string('pob')->nullable();
            $table->datetime('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('homepage')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('work_email')->nullable();
            $table->string('work_address')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('work_fax')->nullable();
            $table->string('marrital_status')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('degree')->nullable();
            $table->string('campus_name')->nullable();
            $table->string('work_position_id')->nullable();
            $table->string('work_position')->nullable();
            $table->string('work_organisation')->nullable();
            $table->string('no_sk')->nullable();
            $table->string('tmt_sk')->nullable();
            $table->string('religion')->nullable();
            $table->string('bracket')->nullable();
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
