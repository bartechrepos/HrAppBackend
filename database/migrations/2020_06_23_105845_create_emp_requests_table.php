<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('emp_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('to_dep_id');
            $table->unsignedBigInteger('request_type_id');
            $table->unsignedBigInteger('request_status_id')->nullable();
            $table->text('notes')->nullable();

            $table->unsignedBigInteger('resp_employee_id')->nullable();
            $table->text('response')->nullable();
            $table->timestamps();
        });

        Schema::connection('mysql')->table('emp_requests', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('to_dep_id')->references('id')->on('departments');
            $table->foreign('request_type_id')->references('id')->on('request_types');
            $table->foreign('request_status_id')->nullable()->references('id')->on('request_statuses');
            $table->foreign('resp_employee_id')->nullable()->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->dropIfExists('emp_requests');
    }
}
