<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Cms\Framework\Database\Migrations\Migration;

class CmsCreatePostMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getPrefix().'post_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->unsigned();
            $table->string('key');
            $table->longText('value')->nullable();
            $table->timestamps();

            $table->foreign('post_id')
                ->references('id')->on($this->getPrefix().'posts')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getPrefix().'post_meta');
    }
}
