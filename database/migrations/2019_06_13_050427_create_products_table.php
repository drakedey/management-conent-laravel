<?php

use App\Libraries\Schema\Utils;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('width');
            $table->bigInteger('large');
            $table->bigInteger('height');
            $table->bigInteger('weight');
            $table->string('code');
            $table->boolean('state')->default(true);
            $table->softDeletes();
            $table->timestamps();
            Utils::getUserRelation($table);
            Utils::getCustomTableRelation($table, 'branches', 'id', 'branch_id' );
            Utils::getCustomTableRelation($table, 'categories', 'id', 'category_id' );
            Utils::getCustomTableRelation($table, 'types', 'id', 'type_id' );
            Utils::getCustomTableRelation($table, 'agreements', 'id', 'agreement_id' );
        });

        Schema::create('products_content', function (Blueprint $table) {
            $table->string('name');
            $table->string('description');
            Utils::getContentTableRelations($table, 'products', 'id', 'product_id' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
