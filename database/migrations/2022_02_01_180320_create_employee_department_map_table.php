<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDepartmentMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_department_map', function (Blueprint $table) {
            $table->foreignId('employee_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('department_id')
                ->constrained()
                ->onDelete('cascade');

            $table->tinyInteger('is_head')->default(0)->comment('0 No, 1 Yes');
            $table->primary(['employee_id', 'department_id'], 'idx_emp_dept');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_department_map');
    }
}
