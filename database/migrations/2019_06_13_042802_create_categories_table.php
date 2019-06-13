<?php

use App\Libraries\Schema\Utils;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->softDeletes();
            $table->boolean('state')->default(true);
            Utils::getUserRelation($table);
            $table->timestamps();
        });

        Schema::create('categories_content', function (Blueprint $table) {
            $table->string('name');
            $table->string('content');
            Utils::getContentTableRelations($table, 'categories', 'id', 'category_id');
        });

        Schema::create('category_country', function (Blueprint $table) {
            Utils::getManyToManyCountryRelation($table, 'category_id', 'categories', 'id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
