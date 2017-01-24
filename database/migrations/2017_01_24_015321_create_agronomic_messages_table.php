<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgronomicMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agronomic_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stage_id')->unsigned();
            $table->string('parameters');
            $table->string('unfavourable_lower_bound');
            $table->string('unfavourable_lower_bound_message');
            $table->string('optional_lower');
            $table->string('optional_message');
            $table->string('unfavourable_upper_bound');
            $table->string('unfavourable_upper_bound_message');
            $table->softDeletes();
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
        Schema::dropIfExists('agronomic_messages');
    }
}
