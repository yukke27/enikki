<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('diaries', function (Blueprint $table) {
            $table->id();
            //constrained()でカラム名に基づいて自動的に参照先テーブルを推測し外部キーが作成される
            //onDelete('cascade')で参照先レコードが削除されたときこのテーブルの関連レコードも削除される
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('weather_id')->constrained('weathers');
            $table->foreignId('color_id')->constrained();
            $table->foreignId('template_id')->constrained('templates');
            $table->date('date');
            $table->string('title');
            //string型だと255文字までだが、短い文章を想定しているため問題ないと考える
            $table->string('body');
            $table->string('image_url');
            $table->timestamps(); //created_atとupdated_atカラムを自動的に追加
            $table->softDeletes(); //deleted_atカラムを追加
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diaries');
    }
};
