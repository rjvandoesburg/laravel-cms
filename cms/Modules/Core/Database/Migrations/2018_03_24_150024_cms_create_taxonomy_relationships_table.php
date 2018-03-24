<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Cms\Framework\Database\Migrations\Migration;

class CmsCreateTaxonomyRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->getPrefix().'taxonomy_relationships', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('term_taxonomy_id')->unsigned();
            $table->integer('object_id')->unsigned();
            $table->string('object_type');
            $table->timestamps();

            $table->unique(['term_taxonomy_id', 'object_id', 'object_type'], 'taxonomy_relationships_unique_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->getPrefix().'taxonomy_relationships');
    }
}
