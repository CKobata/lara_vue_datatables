<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('member_number', 64);
            $table->string('last_name', 32);
            $table->string('first_name', 32);
            $table->integer('sex');
            $table->date('birthday');
            $table->string('email', 128)->unique();
            $table->integer('dept_id');
            $table->string('tel', 32);
            $table->date('join_date');
            $table->date('leave_date')->nullable();
            $table->text('memo')->nullable();
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
        Schema::dropIfExists('members');
    }
}
