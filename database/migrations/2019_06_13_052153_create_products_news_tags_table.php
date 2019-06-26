<?php

use App\Libraries\Schema\Utils;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsNewsTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_tag', function (Blueprint $table) {
            $table->bigIncrements('id');
            Utils::getCustomTableRelation($table, 'products', 'id', 'product_id', null);
            Utils::getCustomTableRelation($table, 'tags', 'id', 'tag_id', null);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('new_tag', function (Blueprint $table) {
            $table->bigIncrements('id');
            Utils::getCustomTableRelation($table, 'news', 'id', 'new_id', null);
            Utils::getCustomTableRelation($table, 'tags', 'id', 'tag_id', null);
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
        Schema::dropIfExists('products_news_tags');
    }
}
