<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // ①ログイン用のテストユーザーを1人作る
        // User::factory() … UserFactory に定義されたダミーデータで作成する
        //   password は UserFactory 側ですでに「password」をハッシュ化した値になっているので、
        //   ここで name と email だけ上書きすれば test@example.com / password でログインできる
        $user = \App\Models\User::factory()->create([
            'name'  => 'テストユーザー',
            'email' => 'test@example.com',
        ]);

        // ②生徒データを作るときに ->for($user) を挟むと、
        //   Student::user() のリレーション（belongsTo）を通じて、自動で user_id に $user->id が入る
        \App\Models\Student::factory(10)->for($user)->create();
    }
}
