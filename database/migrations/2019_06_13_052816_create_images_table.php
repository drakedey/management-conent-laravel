<?php

use App\Libraries\Schema\Utils;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type')->nullable(false);
            $table->string('url')->nullable(false);
            $optional = array('nullable' => true);
            Utils::getCustomTableRelation($table, 'products', 'id', 'product_id', $optional);
            Utils::getCustomTableRelation($table, 'branches', 'id', 'branch_id', $optional);
            Utils::getCustomTableRelation($table, 'news', 'id', 'new_id', $optional);
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
        Schema::dropIfExists('images');
    }
}
