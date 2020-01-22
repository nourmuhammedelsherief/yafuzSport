<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cities', function (Blueprint $table) {
            //
            $table->bigInteger('parent_id')->unsigned()->index()->nullable()->after('name');
            $table->foreign('parent_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
            $table->string('latitude')->after('name');
            $table->string('longitude')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            //
        });
    }
}
