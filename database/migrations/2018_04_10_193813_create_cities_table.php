<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('geonameid');
            $table->string('name',200);
            $table->string('asciiname',200);
            $table->text('alternatenames');
            $table->string('latitude',50);
            $table->string('longitude',50);
            $table->char('feature_class',1);
            $table->string('feature_code',10);
            $table->string('country_code',2);
            $table->string('cc2',200);
            $table->string('admin1_code',20);
            $table->string('admin2_code',80);
            $table->string('admin3_code',20);
            $table->string('admin4_code',20);
            $table->bigInteger('population');
            $table->string('elevation',100);
            $table->string('dem',100);
            $table->string('timezone',40);
            $table->date('modification_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
