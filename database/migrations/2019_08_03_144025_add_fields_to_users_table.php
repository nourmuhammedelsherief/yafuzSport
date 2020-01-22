<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->bigInteger('driver_id')->unsigned()->index()->nullable()->after('name');
            $table->foreign('driver_id')
                ->references('id')->on('drivers')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->bigInteger('nationality_id')->unsigned()->index()->nullable()->after('name');
            $table->foreign('nationality_id')
                ->references('id')->on('nationalities')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->bigInteger('age_id')->unsigned()->index()->nullable()->after('name');
            $table->foreign('age_id')
            ->references('id')->on('ages')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->bigInteger('company_id')->unsigned()->index()->nullable()->after('name');
            $table->foreign('company_id')
            ->references('id')->on('companies')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->bigInteger('car_model_id')->unsigned()->index()->nullable()->after('name');
            $table->foreign('car_model_id')
            ->references('id')->on('car_models')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->bigInteger('city_mode_id')->unsigned()->index()->nullable()->after('name');
            $table->foreign('city_mode_id')
            ->references('id')->on('model_cities')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->bigInteger('passenger_id')->unsigned()->index()->nullable()->after('name');
            $table->foreign('passenger_id')
            ->references('id')->on('passengers')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->bigInteger('city_id')->unsigned()->index()->nullable()->after('name');
            $table->foreign('city_id')
            ->references('id')->on('cities')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->bigInteger('region_id')->unsigned()->index()->nullable()->after('name');
            $table->foreign('region_id')
            ->references('id')->on('cities')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
