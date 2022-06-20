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
    public function up(){

        Schema::table('queue_call_entry', function (Blueprint $table) {
           $table->string('description')->default('')->after('queue');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){

        Schema::table('queue_call_entry', function (Blueprint $table) {
                $table->dropColumn('description');
        });

    }
};
