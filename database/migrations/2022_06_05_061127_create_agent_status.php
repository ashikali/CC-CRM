<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_status', function (Blueprint $table) {
            $table->integer('queue_id');
	    $table->integer('on_break')->default(0);
	    $table->integer('on_call')->default(0);
	    $table->integer('on_ready')->default(0);
	    $table->integer('on_logged_in')->default(0);
	    $table->primary('queue_id'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_status');
    }
};
