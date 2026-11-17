<?php
// ▼ database/migrations/2026_07_16_000001_add_user_id_to_students_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 既存の students テーブルに user_id カラムを追加する
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('user_id') // users.id を参照する外部キーカラムを作る
                ->after('id')             // id カラムの直後に配置する（見やすさのため）
                ->constrained()           // 参照先（users テーブルの id）を自動推測して外部キー制約を張る
                ->cascadeOnDelete();      // ユーザーが削除されたら、そのユーザーの生徒データも一緒に削除する
        });
    }

    public function down(): void
    {
        // migrate:rollback したときに、追加した外部キーとカラムを元に戻す
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // 先に外部キー制約を外す
            $table->dropColumn('user_id');    // カラム自体を削除する
        });
    }
};