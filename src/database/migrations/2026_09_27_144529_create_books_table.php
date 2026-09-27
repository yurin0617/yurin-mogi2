<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 登録者ID
            $table->string('title'); // 書籍タイトル
            $table->string('author'); // 著者名
            $table->string('isbn')->unique()->nullable(); // ISBN
            $table->date('published_date')->nullable(); // 出版日
            $table->text('description')->nullable(); // 書籍の説明
            $table->string('image_url')->nullable(); // 画像URL
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
        Schema::dropIfExists('books');
    }
}
