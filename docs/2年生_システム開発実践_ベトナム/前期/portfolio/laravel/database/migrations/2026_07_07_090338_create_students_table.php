<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();                              // ID（自動で連番）
            $table->string('name', 100);              // 氏名
            $table->string('email')->unique();         // メールアドレス（重複不可）
            $table->integer('score')->default(0);     // 点数（初期値は0）
            $table->timestamps();                      // 作成日時・更新日時（自動）
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
