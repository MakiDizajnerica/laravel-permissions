<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyConstraintsToPermissionUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permission_user', function (Blueprint $table) {
            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permission_user', function (Blueprint $table) {
            $table->dropForeign([
                'permission_id',
                'user_id',
            ]);
        });
    }
}
