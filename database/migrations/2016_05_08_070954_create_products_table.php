<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mongo_id', 24);
            $table->integer('template_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->integer('bot_count');
            $table->text('bot_names');
            $table->decimal('price', 10, 2);
            $table->integer('start_date')->unsigned();
            $table->enum('type', ['beginning', 'ticket', 'common']);
            $table->double('coefficient', '5', 3);
            $table->enum('source', ['common', 'game_zone']);
            $table->integer('status');
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('templates');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');
    }
}
