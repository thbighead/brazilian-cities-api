<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueToNameAndStateIdToCitiesTable extends Migration
{
    const TABLE = 'cities';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(self::TABLE, function (Blueprint $table) {
            $table->unique(['state_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(self::TABLE, function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropUnique(['state_id', 'name']);
            $table->foreign('state_id')->references('id')->on('states');
        });
    }
}
