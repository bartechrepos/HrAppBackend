<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditRequestTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::connection('mysql')->table('request_types', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedBigInteger('to_dep_id');
        });
        Schema::connection('mysql')->table('request_types', function (Blueprint $table) {
            $table->foreign('to_dep_id')->references('id')->on('departments');
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
