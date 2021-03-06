<?php

use App\Libraries\Schema\Utils;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            Utils::getUserRelation($table);
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
//            $table->string('type')->nullable(false);
//            $table->string('url')->nullable(false);
            $table->timestamps();
            Utils::getUserRelation($table);
            Utils::getCustomTableRelation($table, 'branch_types', 'id', 'branch_type_id', null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
