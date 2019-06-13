<?php

use App\Libraries\Schema\Utils;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesPermisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('modules', function(Blueprint $table) {
           $table->bigIncrements('id');
           $table->string('name')->nullable(false);
           $table->softDeletes();
           $table->timestamps();
        });

        Schema::create('module_rol', function(Blueprint $table) {
            $table->bigIncrements('id');
            Utils::getCustomTableRelation($table, 'roles', 'id', 'rol_id');
            Utils::getCustomTableRelation($table, 'modules', 'id', 'module_id');
            $table->boolean('create')->default(false);
            $table->boolean('read')->default(false);
            $table->boolean('update')->default(false);
            $table->boolean('delete')->default(false);
        });

        Schema::table('users', function (Blueprint $table) {
            if(Schema::hasColumn('users', 'rol')) {
                $table->dropColumn('rol');
            }
            Utils::getCustomTableRelation($table, 'roles', 'id', 'rol_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
