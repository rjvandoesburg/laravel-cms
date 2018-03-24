<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Cms\Framework\Database\Migrations\Migration;

class CmsCreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getPrefix().'posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author_id')->unsigned();
            $table->string('title');
            $table->longText('content')->nullable()->default(null);
            $table->string('type')->nullable()->default(null);
            $table->string('status')->nullable()->default(null);
            $table->string('slug')->nullable()->default(null);
            $table->integer('parent_id')->unsigned()->nullable()->default(null);
            $table->integer('creator_id')->unsigned();

            $table->timestamps();
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();

            $table->foreign('author_id')
                ->references('id')->on($this->getPrefix().'users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('creator_id')
                ->references('id')->on($this->getPrefix().'users')
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
        Schema::dropIfExists($this->getPrefix().'posts');
    }
}
