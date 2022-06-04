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
        Schema::table('call_entry', function (Blueprint $table) {
   	    $table->string('c_status')->nullable()->default('Open');
            $table->string('c_action')->nullable()->default('Not Called');
            $table->longText('c_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('call_entry', function (Blueprint $table) {
            //
        });
    }
};
