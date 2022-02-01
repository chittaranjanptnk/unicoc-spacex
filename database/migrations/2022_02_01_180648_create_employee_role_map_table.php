<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeRoleMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_role_map', function (Blueprint $table) {
            $table->foreignId('employee_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('role_id')
                ->constrained()
                ->onDelete('cascade');

            $table->primary(['employee_id', 'role_id'], 'idx_emp_role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_role_map');
    }
}
