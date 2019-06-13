<?php

use App\Libraries\Schema\Utils;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('state')->default(true);
            Utils::getUserRelation($table);
            $table->timestamps();
        });

        Schema::create('news_content', function (Blueprint $table) {
            $table->string('title');
            $table->string('body');
            Utils::getContentTableRelations($table, 'news', 'id', 'new_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news');
    }
}
