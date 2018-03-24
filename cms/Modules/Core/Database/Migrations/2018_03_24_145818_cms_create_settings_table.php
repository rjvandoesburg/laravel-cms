<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Cms\Framework\Database\Migrations\Migration;

class CmsCreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getPrefix().'settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('value');
            $table->boolean('autoload')->default(false);
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
        Schema::dropIfExists($this->getPrefix().'settings');
    }
}
