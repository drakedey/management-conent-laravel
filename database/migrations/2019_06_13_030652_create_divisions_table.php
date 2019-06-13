<?php

use App\Libraries\Schema\Utils;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->softDeletes();
            Utils::getUserRelation($table);
        });

        Schema::create('divisions_content', function (Blueprint $table) {
            $table->string('name');
            Utils::getContentTableRelations($table, 'divisions', 'id', 'division_id');
        });

        Schema::create('country_division', function (Blueprint $table) {
            $table->bigIncrements('id');
            Utils::getCustomTableRelation($table, 'countries', 'id', 'country_id');
            Utils::getCustomTableRelation($table, 'divisions', 'id', 'division_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('divisions');
    }
}
