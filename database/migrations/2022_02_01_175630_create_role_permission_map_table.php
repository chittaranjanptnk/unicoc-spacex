<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolePermissionMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_permission_map', function (Blueprint $table) {
            $table->foreignId('role_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('permission_id')
                ->constrained()
                ->onDelete('cascade');

            $table->primary(['role_id', 'permission_id'], 'idx_role_perm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_permission_map');
    }
}