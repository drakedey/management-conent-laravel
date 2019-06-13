<?php

namespace App\Libraries\Schema;

use Illuminate\Database\Schema\Blueprint;

class Utils
{
    /**
     * @param Blueprint $table the instance of the blue print
     * @param string $tableRef the name of the table that is going to be indexed
     * @param string $tableRefId the id of the table that's going to be indexed
     * @param string $foreingId the name of the id on table
     *
     * function to index a standard _content type table
     */
    public static function getContentTableRelations(Blueprint $table, string $tableRef, string $tableRefId, string $foreingId) {
        $table->increments('id');
        $table->timestamps();
        $table->softDeletes();
        $table->bigInteger($foreingId)->unsigned()->nullable(false);
        $table->bigInteger('language_id')->unsigned()->nullable(false);
        $table->foreign($foreingId)->on($tableRef)->references($tableRefId)
            ->onUpdate('CASCADE')
            ->onDelete('CASCADE');
        $table->foreign('language_id')->on('languages')->references('id');
    }

    /**
     * @param Blueprint $table reference to table
     * set the columns and the index for user ona to many relation ship
     */
    public static function getUserRelation(Blueprint $table) {
        $table->bigInteger('user_id')->unsigned()->nullable(false);
        $table->foreign('user_id')->on('users')->references('id');
    }

    /**
     * @param Blueprint $table Blueprint Instance
     * @param string $foreingId name of the foreign key on $table
     * @param string $tableRef table to be related to country
     * @param string $tableRefId id of the $tableRef
     *
     * Function to make many to many relationship between country and the given table
     */
    public static function getManyToManyCountryRelation(Blueprint $table, string $foreingId, string $tableRef, string $tableRefId) {
        $table->increments('id');
        $table->timestamps();
        $table->softDeletes();
        $table->bigInteger($foreingId)->unsigned()->nullable(false);
        $table->bigInteger('country_id')->unsigned()->nullable(false);
        $table->foreign($foreingId)->on($tableRef)->references($tableRefId)
            ->onUpdate('CASCADE')
            ->onDelete('CASCADE');
        $table->foreign('country_id')->on('languages')->references('id');
    }

    public static function getCustomTableRelation(Blueprint $table, string $tableRef, string $tableRefId,string $foreignId) {
        $table->bigInteger($foreignId)->unsigned()->nullable(false);
        $table->foreign($foreignId)->on($tableRef)->references($tableRefId);
    }

}