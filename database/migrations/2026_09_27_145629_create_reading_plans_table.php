<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadingPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reading_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 計画者ID（カスケード削除）
            $table->foreignId('book_id')->constrained()->cascadeOnDelete(); // 対象書籍ID（カスケード削除）
            $table->date('target_date'); // 読書予定期日
            $table->string('status', 50); // 状態（planned / in_progress / completed / paused）
            $table->timestamp('completed_at')->nullable(); // 完了日時（NULL許可）
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
        Schema::dropIfExists('reading_plans');
    }
}
